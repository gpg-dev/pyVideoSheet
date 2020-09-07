const _ = require('lodash');
const Joi = require('joi');

async function updateReviewScoreUser (userId) {
  try {
    const data = await DB.Review.aggregate([{
      $match: {
        userId: Helper.App.toObjectId(userId)
      }
    }, {
      $group: {
        _id: null,
        sum: { $sum: '$rating' },
        count: { $sum: 1 }
      }
    }]).exec();
    
    if (!data || !data.length) {
      return false;
    }

    const sum = data[0].sum;
    const count = data[0].count || 1;
    const avg = Math.round(sum / count, 2)

    await DB.User.update({ _id: userId }, {
      $set: {
        ratingAvg: avg,
        totalRating: count,
        ratingScore: sum
      }
    });
  } catch (e) {
    throw e;
  }
}

exports.findOne = async (req, res, next) => {
  try {
    const review = await DB.Review.findOne({ _id: req.params.reviewId }).populate('rater');
    if (!review) {
      return res.status(404).send(PopulateResponse.notFound());
    }

    req.review = review;
    res.locals.review = review;
    return next();
  } catch (e) {
    return next(e);
  }
};

/**
 * Create a new rating
 */
exports.create = async (req, res, next) => {
  try {
    const validateSchema = Joi.object().keys({
      userId: Joi.string().required(),
      rating: Joi.number().min(1).max(5).required(),
      comment: Joi.string().allow([null, '']).optional()
    });
    const validate = Joi.validate(req.body, validateSchema);
    if (validate.error) {
      return next(PopulateResponse.validationError(validate.error));
    }

    const review = new DB.Review(Object.assign(validate.value, {
      rateBy: req.user._id
    }));
    await review.save();
    await updateReviewScoreUser(validate.value.userId);
    res.locals.review = review;
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
    const validateSchema = Joi.object().keys({
      rating: Joi.number().min(1).max(5).optional(),
      comment: Joi.string().allow([null, '']).optional()
    });
    const validate = Joi.validate(req.body, validateSchema);
    if (validate.error) {
      return next(PopulateResponse.validationError(validate.error));
    }

    if (req.user.role !== 'admin' && req.user._id.toString() === req.review.rateBy.toString()) {
      return next(PopulateResponse.forbidden());
    }
    
    _.merge(req.review, validate.value);
    await req.review.save();
    await updateReviewScoreUser(req.review.userId);
    res.locals.update = req.review;
    return next();
  } catch (e) {
    return next(e);
  }
};

exports.remove = async (req, res, next) => {
  try {
    await req.review.remove();
    await updateReviewScoreUser(req.review.userId);
    res.locals.remove = {
      message: 'Review is deleted'
    };
    next();
  } catch (e) {
    next(e);
  }
};

/**
 * get list review
 */
exports.list = async (req, res, next) => {
  const page = Math.max(0, req.query.page - 1) || 0; // using a zero-based page index for use with skip()
  const take = parseInt(req.query.take, 10) || 10;

  try {
    const query = Helper.App.populateDbQuery(req.query, {
      equal: ['userId', 'rateBy']
    });

    const sort = Helper.App.populateDBSort(req.query);
    const count = await DB.Review.count(query);
    const items = await DB.Review.find(query)
      .populate('rater')
      .sort(sort).skip(page * take).limit(take)
      .exec();

    res.locals.list = {
      count,
      items
    };
    next();
  } catch (e) {
    next(e);
  }
};
