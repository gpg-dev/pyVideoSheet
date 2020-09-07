const _ = require('lodash');
const dialCodes = require('../dialCodes.json');

function getDialCodeFromCountryCode(countryCode) {
  const item = _.find(dialCodes, code => code.code.toLowerCase() === countryCode.toLowerCase());
  return item ? item.dialCode : null;
}

exports.search = async (req, res, next) => {
  try {
    const page = Math.max(0, req.query.page - 1) || 0; // using a zero-based page index for use with skip()
    const take = parseInt(req.query.take, 10) || 10;

    if (req.query.city) {
      req.query.baseCity = req.query.city;
    }

    const booleanFields = ['featured', 'realPics', 'withVideo', 'pornstar', 'naturalPhoto']; // 'verifiedContact',
    const query = Helper.App.populateDbQuery(req.query, {
      text: req.user && req.user.role === 'admin' ? ['name', 'username', 'email'] : ['username', 'baseCity'],
      boolean: req.user && req.user.role === 'admin' ? ['emailVerified', 'isActive', 'phoneVerified'].concat(booleanFields) : booleanFields
    });
    query.type = 'escort';
    if (['true', '1'].indexOf(req.query.verifiedContact) > -1) {
      query.phoneVerified = true;
    } else if (['false', ''].indexOf(req.query.verifiedContact) > -1) {
      query.phoneVerified = false;
    }

    if (req.query.country) {
      const dialCode = getDialCodeFromCountryCode(req.query.country);
      if (dialCode) {
        query.dialCode = new RegExp(`.*\\${dialCode}.*`, 'i');
      }
    }

    if (!req.user || req.user.role !== 'admin') {
      query.isActive = true;
      query.emailVerified = true;
    }

    const sort = Helper.App.populateDBSort(req.query);
    const count = await DB.User.count(query);
    const items = await DB.User.find(query).sort(sort).skip(page * take).limit(take)
      .exec();

    if (!req.user || (!req.user.subscribed && req.user.role !== 'admin')) {
      Service.Escort.hidePrivateData(items);
    }

    res.locals.search = {
      count,
      items
    };
    next();
  } catch (e) {
    next(e);
  }
};

exports.findDetails = async (req, res, next) => {
  try {
    const query = {
      isActive: true
    };
    if (Helper.App.isMongoId(req.params.id)) {
      query._id = req.params.id;
    } else {
      query.username = req.params.id;
    }
    const escort = await DB.User.findOne(query);
    if (!escort) {
      return next(PopulateResponse.notFound());
    }

    // load related assets
    // TODO - check more query filter here
    const media = await DB.Media.find({ ownerId: escort.id }).sort({ createdAt: -1 }).exec();
    if (!req.user || (!req.user.subscribed && req.user.role !== 'admin' && req.user._id.toString() !== escort._id.toString())) {
      Service.Escort.hidePrivateData(escort);
    }

    res.locals.details = {
      escort,
      media
    };
    return next();
  } catch (e) {
    return next(e);
  }
};

exports.media = async (req, res, next) => {
  try {
    const media = await DB.Media
      .find({ ownerId: req.user._id })
      .sort({ createdAt: -1 })
      .exec();

    res.locals.media = media;
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
    const fields = _.pick(req.body, [
      'name', 'password', 'address', 'username',
      'gender', 'orientation', 'age', 'languages', 'serviceFor', 'baseCity', 'phoneDialCode',
      'availableTime', 'about', 'firstName', 'lastName', 'availableTravel', 'rates'
    ]);
    if (req.user.role === 'admin') {
      _.merge(fields, _.pick(req.body, [
        'isActive', 'emailVerified', 'phoneVerified', 'realPics', 'featured', 'phoneNumber'
      ]));
    }
    if (!fields.password) {
      delete fields.password;
    }
    _.assign(user, fields);
    await user.save();

    res.locals.update = user;
    next();
  } catch (e) {
    next(e);
  }
};

/**
 * create a new escort
 */
exports.create = async (req, res, next) => {
  try {
    const fields = _.pick(req.body, [
      'name', 'password', 'address', 'username', 'email', 'featured',
      'gender', 'orientation', 'age', 'languages', 'serviceFor', 'baseCity', 'phoneDialCode',
      'availableTime', 'about', 'firstName', 'lastName', 'availableTravel', 'rates',
      'isActive', 'emailVerified', 'phoneVerified', 'realPics', 'phoneNumber'
    ]);

    if (!fields.username || !fields.email) {
      return next(PopulateResponse.validationError({
        username: fields.username ? '' : 'Username is required',
        email: fields.email ? '' : 'Email is required',
        message: 'Username and email is required'
      }));
    }

    // check email and username
    const usernameCheck = await DB.User.count({ username: fields.username.toLowerCase() });
    if (usernameCheck) {
      return next(PopulateResponse.error({
        message: 'Username has already taken'
      }, 'ERR_USERNAME_EXISIT'));
    }

    const emailCheck = await DB.User.count({ email: fields.email.toLowerCase() });
    if (emailCheck) {
      return next(PopulateResponse.error({
        message: 'Email has already taken'
      }, 'ERR_EMAIL_EXISIT'));
    }

    const user = new DB.User(fields);
    user.type = 'escort';
    await user.save();

    res.locals.create = user;
    return next();
  } catch (e) {
    return next(e);
  }
};
