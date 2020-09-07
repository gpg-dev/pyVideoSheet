const Joi = require('joi');

const validateSchema = Joi.object().keys({
  escortId: Joi.string().required(),
  startTime: Joi.number().required(),
  duration: Joi.string().required(),
  date: Joi.string().required()
}).unknown();

exports.create = async (req, res, next) => {
  try {
    const validate = Joi.validate(req.body, validateSchema);
    if (validate.error) {
      return next(PopulateResponse.validationError(validate.error));
    }

    const booking = new DB.Booking(Object.assign(validate.value, {
      status: 'waiting-payment',
      userId: req.user._id
    }));
    await booking.save();
    res.locals.booking = booking;
    return next();
  } catch (e) {
    return next(e);
  }
};

/**
 * get list booking
 */
exports.list = async (req, res, next) => {
  const page = Math.max(0, req.query.page - 1) || 0; // using a zero-based page index for use with skip()
  const take = parseInt(req.query.take, 10) || 10;

  try {
    const query = Helper.App.populateDbQuery(req.query, {
      text: ['name', 'email', 'phoneNumber']
    });
    query.status = { $ne: 'waiting-payment' };
    if (req.user.role !== 'admin') {
      if (req.user.type === 'escort') {
        query.escortId = req.user._id;
      } else {
        query.userId = req.user._id;
      }
    }

    const sort = Helper.App.populateDBSort(req.query);
    const count = await DB.Booking.count(query);
    const items = await DB.Booking.find(query)
      .populate('escort')
      .populate('user')
      .sort(sort)
      .skip(page * take)
      .limit(take)
      .exec();

    res.locals.list = {
      count,
      items
    };
    next();
  } catch (e) {
    next();
  }
};

/**
 * details of the booking
 */
exports.details = async (req, res, next) => {
  try {
    const query = { _id: req.params.bookingId };
    if (req.user.role !== 'admin') {
      query.$or = [{
        escortId: req.user._id
      }, {
        userId: req.user._id
      }];
    }

    const booking = await DB.Booking.findOne(query)
      .populate('escort')
      .populate('user')
      .exec();

    res.locals.booking = booking;
    next();
  } catch (e) {
    next();
  }
};
