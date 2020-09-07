const _ = require('lodash');
const Joi = require('joi');
const fs = require('fs');
const path = require('path');

/**
 * Create a new user
 */
exports.create = async (req, res, next) => {
  try {
    const schema = Joi.object().keys({
      email: Joi.string().email().required(),
      password: Joi.string().min(6).required()
    }).unknown();

    const validate = Joi.validate(req.body, schema);
    if (validate.error) {
      return next(PopulateResponse.validationError(validate.error));
    }

    const data = req.body;
    if (data.role !== 'admin') {
      data.role = 'user';
    }

    const user = await Service.User.create(data);
    res.locals.user = user;
    return next();
  } catch (e) {
    return next(e);
  }
};

/**
 * do update for user profile or admin update
 */
exports.update = async (req, res, next) => {
  try {
    const user = req.params.id ? await DB.User.findOne({ _id: req.params.id }) : req.user;
    let publicFields = [
      'name', 'password', 'address', 'phoneNumber', 'baseCity', 'firstName', 'about', 'gender', 'age', 'dialCode'
    ];
    if (req.user.role === 'admin') {
      publicFields = publicFields.concat([
        'isActive', 'emailVerified', 'role'
      ]);
    }
    const fields = _.pick(req.body, publicFields);

    _.merge(user, fields);
    await user.save();

    res.locals.update = user;
    next();
  } catch (e) {
    next(e);
  }
};

exports.me = (req, res, next) => {
  res.locals.me = req.user;
  next();
};

exports.findOne = async (req, res, next) => {
  try {
    const user = await DB.User.findOne({
      _id: req.params.id
    });

    if (!req.user || !req.user.subscribed) {
      Service.Escort.hidePrivateData(user);
    }

    res.locals.user = user;
    next();
  } catch (e) {
    next(e);
  }
};

/**
 * update user avatar
 */
exports.updateAvatar = async (req, res, next) => {
  try {
    const user = req.params.id ? await DB.User.findOne({ _id: req.params.id }) : req.user;
    if (!user) {
      return next(PopulateResponse.notFound());
    }

    await DB.User.update({
      _id: req.params.id || req.user._id
    }, {
      $set: {
        avatar: req.file.path
      }
    });

    // unlink old avatar
    if (user.avatar && fs.existsSync(path.resolve(user.avatar))) {
      fs.unlinkSync(path.resolve(user.avatar));
    }

    res.locals.updateAvatar = {
      url: DB.User.getAvatarUrl(req.file.path)
    };

    return next();
  } catch (e) {
    return next(e);
  }
};

exports.search = async (req, res, next) => {
  const page = Math.max(0, req.query.page - 1) || 0; // using a zero-based page index for use with skip()
  const take = parseInt(req.query.take, 10) || 10;

  try {
    const query = Helper.App.populateDbQuery(req.query, {
      text: ['name', 'phoneNumber', 'email', 'username'],
      boolean: ['isActive', 'phoneVerified', 'emailVerified'],
      equal: ['role', 'type']
    });

    const sort = Helper.App.populateDBSort(req.query);
    const count = await DB.User.count(query);
    const items = await DB.User.find(query)
      .sort(sort)
      .skip(page * take)
      .limit(take)
      .exec();

    res.locals.search = {
      count,
      items
    };
    next();
  } catch (e) {
    next(e);
  }
};

exports.remove = async (req, res, next) => {
  try {
    const user = DB.User.findOne({ _id: req.params.userId });
    if (!user) {
      return next(PopulateResponse.notFound());
    }

    if (user.role === 'admin') {
      return next(PopulateResponse.forbidden());
    }

    await user.remove();
    res.locals.remove = {
      success: true
    };
    return next();
  } catch (e) {
    return next(e);
  }
};
