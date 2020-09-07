const escortController = require('../controllers/escort.controller');

module.exports = (router) => {
  /**
   * @apiDefine escortRequest
   * @apiParam {String}   [username]  unique `username`. ALlowable for admin only
   * @apiParam {String}   [email]      email address
   * @apiParam {String}   [password]   password
   * @apiParam {String}   [name]
   * @apiParam {String}   [address]
   * @apiParam {String}   [phoneNumber]
   * @apiParam {String}   [gender]
   * @apiParam {String}   [orientation]
   * @apiParam {Number}   [age]
   * @apiParam {String[]}   [languages]
   * @apiParam {String}   [serviceFor]
   * @apiParam {String}   [baseCity]
   * @apiParam {Object}   [availableTime] store 0 - 6 (mean Sunday to Saturday)
   *                                      `{ 0: { from, to, available: Boolean } }`
   */

  /**
   * @apiGroup Escort
   * @apiVersion 1.0.0
   * @api {get} /escorts/search?:sort&:sortType  Search escort
   * @apiHeader {String}    Authorization       Authorization token
   * @apiParam {String}   [sort]
   * @apiParam {String}   [sortType]  asc or desc
   *
   * @apiPermission all
   */
  router.get(
    '/v1/escorts/search',
    Middleware.loadUser,
    escortController.search,
    Middleware.Response.success('search')
  );

  /**
   * @apiGroup Escort
   * @apiVersion 1.0.0
   * @api {get} /escorts/media  get my media
   * @apiParam {String}   id escort unique id
   *
   * @apiPermission all
   */
  router.get(
    '/v1/escorts/media',
    Middleware.isAuthenticated,
    escortController.media,
    Middleware.Response.success('media')
  );

  /**
   * @apiGroup Escort
   * @apiVersion 1.0.0
   * @api {get} /escorts/:id  Find escort details
   * @apiParam {String}   id escort unique id
   *
   * @apiPermission all
   */
  router.get(
    '/v1/escorts/:id',
    Middleware.loadUser,
    escortController.findDetails,
    Middleware.Response.success('details')
  );

  /**
   * @apiGroup Escort
   * @apiVersion 1.0.0
   * @api {put} /v1/escorts Update current user profile
   * @apiDescription Update profile
   * @apiUse authRequest
   * @apiUse escortRequest
   *
   * @apiPermission escort
   */
  router.put(
    '/v1/escorts',
    Middleware.isAuthenticated,
    escortController.update,
    Middleware.Response.success('update')
  );

  /**
   * @apiGroup Escort
   * @apiVersion 1.0.0
   * @api {put} /v1/escorts/:id Update profile
   * @apiDescription Update profile
   * @apiUse authRequest
   * @apiParam {String}   id      escort id
   * @apiUse escortRequest
   *
   * @apiPermission admin
   */
  router.put(
    '/v1/escorts/:id',
    Middleware.hasRole('admin'),
    escortController.update,
    Middleware.Response.success('update')
  );

  /**
   * @apiGroup Escort
   * @apiVersion 1.0.0
   * @api {post} /v1/escorts Create new escort
   * @apiDescription Create new escort
   * @apiUse authRequest
   * @apiUse escortRequest
   *
   * @apiPermission escort
   */
  router.post(
    '/v1/escorts',
    Middleware.hasRole('admin'),
    escortController.create,
    Middleware.Response.success('create')
  );
};
