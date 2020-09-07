const controller = require('./language.controller');

exports.router = (router) => {
  /**
   * @apiGroup Language
   * @apiVersion 1.0.0
   * @api {get} /languages  Get list languages of site
   *
   * @apiPermission all
   */
  router.get(
    '/v1/languages',
    controller.list,
    Middleware.Response.success('languages')
  );
};