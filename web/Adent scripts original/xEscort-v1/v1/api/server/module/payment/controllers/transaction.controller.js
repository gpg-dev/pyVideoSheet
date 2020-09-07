const Joi = require('joi');
const _ = require('lodash');
const url = require('url');
const nconf = require('nconf');

const Payment = require('../components/Payment');

const SUBSCRIPTION_ESCORT_PRICE = 10;
const SUBSCRIPTION_USER_PRICE = 10;
const BOOKING_PRICE = 10;

const validateSchema = Joi.object().keys({
  gateway: Joi.string().required(),
  service: Joi.string().allow(['escort_subscription', 'user_subscription', 'booking']).required(),
  redirectSuccessUrl: Joi.string().optional(),
  redirectCancelUrl: Joi.string().optional(),
  itemId: Joi.string().allow(['', null]).optional() // for booking
});

exports.request = async (req, res, next) => {
  try {
    const validate = Joi.validate(req.body, validateSchema);
    if (validate.error) {
      return next(PopulateResponse.validationError(validate.error));
    }
    const value = validate.value;
    let price = BOOKING_PRICE;
    if (value.service === 'escort_subscription') {
      const escortSubcription = await DB.Config.findOne({ key: 'subscriptionEscortPrice' });
      price = escortSubcription ? escortSubcription.value : SUBSCRIPTION_ESCORT_PRICE;
    } else if (value.service === 'user_subscription') {
      const userSubcription = await DB.Config.findOne({ key: 'subscriptionUserPrice' });
      price = userSubcription ? userSubcription.value : SUBSCRIPTION_USER_PRICE;
    }

    const paymentData = {
      gateway: 'paypal',
      price,
      returnUrl: url.resolve(nconf.get('baseUrl'), '/v1/payment/paypal/callback?action=success'),
      cancelUrl: url.resolve(nconf.get('baseUrl'), '/v1/payment/paypal/callback?action=cancel'),
      meta: value,
      userId: req.user._id
    };

    if (['escort_subscription', 'user_subscription'].indexOf(value.service) > -1) {
      _.merge(paymentData, {
        subscriptionType: value.service,
        name: value.service === 'escort_subscription' ? 'Monthly subscription featured escort' : 'Monthly subscription website',
        description: value.service === 'escort_subscription' ? 'Monthly subscription featured escort' : 'Monthly subscription website'
      });
    } else {
      const booking = await DB.Booking.findOne({ _id: validate.value.itemId })
        .populate('escort');

      if (!booking || !booking.escort) {
        throw new Error('Booking not found');
      }

      const bookingPriceConf = await DB.Config.findOne({ key: 'bookingPrice' });
      const bookingPrice = bookingPriceConf ? bookingPriceConf.value : BOOKING_PRICE;

      const name = `Booking ${booking.escort.username}`;
      _.merge(paymentData, {
        type: 'booking',
        itemId: booking._id,
        price: bookingPrice,
        name,
        description: name
      });
    }

    const data = ['escort_subscription', 'user_subscription'].indexOf(value.service) > -1 ?
      await Payment.createSubscriptionTransaction(paymentData) :
      await Payment.createSinglePayment(paymentData);

    res.locals.request = {
      redirectUrl: data.redirectUrl
    };
    return next();
  } catch (e) {
    return next(e);
  }
};
