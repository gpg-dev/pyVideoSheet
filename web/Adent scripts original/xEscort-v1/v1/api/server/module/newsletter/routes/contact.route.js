const contactController = require('../controllers/contact.controller');

module.exports = (router) => {
  /**
   * @apiGroup Newsletter
   * @apiVersion 1.0.0
   * @api {post} /v1/newsletter/contact Register contact
   * @apiDescription Register as newsletter member
   * @apiUse authRequest
   * @apiParam {String} email
   * @apiParam {String} [name]
   * @apiParam {String} [address]
   * @apiSuccessExample {json} Success-Response
   * {
   *    "code": 200,
   *    "message": "OK",
   *    "data": {
   *      "success": true
   *    },
   *    "error": false
   * }
   * @apiPermission all
   */
  router.post(
    '/v1/newsletter/contact',
    contactController.register,
    Middleware.Response.success('register')
  );
};
