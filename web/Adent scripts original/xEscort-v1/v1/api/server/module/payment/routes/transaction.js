const transactionController = require('../controllers/transaction.controller');

module.exports = (router) => {
  /**
   * @apiDefine transactionRequest
   * @apiParam {String}   gateway        `paypal` for now
   * @apiParam {String}   service        `escort_subscription`, `user_subscription`, `booking`
   * @apiParam {String}   redirectSuccessUrl
   * @apiParam {String}   redirectCancelUrl
   */

  /**
   * @apiGroup Payment
   * @apiVersion 1.0.0
   * @api {post} /v1/payment/transactions/request  Create transacation
   * @apiDescription create transaction and get redirect url
   * @apiPermission user
   */
  router.post(
    '/v1/payment/transactions/request',
    Middleware.isAuthenticated,
    transactionController.request,
    Middleware.Response.success('request')
  );
};
