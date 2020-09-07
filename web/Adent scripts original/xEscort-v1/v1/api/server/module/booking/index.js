const bookingController = require('./controllers/booking.controller');

exports.model = {
  Booking: require('./models/booking')
};

exports.mongoosePlugin = require('./mongoosePlugin');

exports.services = {
  Booking: require('./services/booking')
};

exports.router = (router) => {
  /**
   * @apiDefine bookingRequest
   * @apiParam {String}   escortId  escort uuid
   * @apiParam {String}   [name] custom name
   * @apiParam {String}   [email]
   * @apiParam {String}   [phoneNumber]
   * @apiParam {String}   [city]
   * @apiParam {String}   date date for this booking. Format in js date format
   * @apiParam {Number}   startTime time in date in minute
   * @apiParam {String}   duration duration from the list rate of escort
   * @apiParam {String}   [preferredBy] preerred user name
   * @apiParam {String}   [clothingStyle]
   * @apiParam {String}   [message]
   */

  /**
   * @apiGroup Booking
   * @apiVersion 1.0.0
   * @api {post} /v1/booking Create a new booking
   * @apiDescription Create a new booking
   * @apiUse authRequest
   * @apiUse bookingRequest
   * @apiPermission all
   */
  router.post(
    '/v1/booking',
    Middleware.isAuthenticated,
    bookingController.create,
    Middleware.Response.success('booking')
  );

  /**
   * @apiGroup Booking
   * @apiVersion 1.0.0
   * @api {get} /v1/booking Get list booking
   * @apiDescription Get list booking
   * @apiUse authRequest
   * @apiPermission all
   */
  router.get(
    '/v1/booking',
    Middleware.isAuthenticated,
    bookingController.list,
    Middleware.Response.success('list')
  );

  /**
   * @apiGroup Booking
   * @apiVersion 1.0.0
   * @api {get} /v1/booking/:bookingId Get deails booking
   * @apiDescription Get deails booking
   * @apiUse authRequest
   * @apiParam bookingId uui of booking
   * @apiPermission all
   */
  router.get(
    '/v1/booking/:bookingId',
    Middleware.isAuthenticated,
    bookingController.details,
    Middleware.Response.success('booking')
  );
};
