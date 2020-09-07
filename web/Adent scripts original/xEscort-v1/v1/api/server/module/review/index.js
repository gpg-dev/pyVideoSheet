const reviewController = require('./controllers/review.controller');

exports.model = {
  Review: require('./models/review')
};

exports.mongoosePlugin = require('./mongoosePlugin');

exports.router = (router) => {
  /**
   * @apiGroup Review
   * @apiVersion 1.0.0
   * @api {get} /v1/reviews?:userId&:rateBy  Get list categories
   * @apiDescription Get list categories
   * @apiParam {String}   [name]      review name
   * @apiParam {String}   [alias]     review alias
   * @apiPermission all
   */
  router.get(
    '/v1/reviews',
    reviewController.list,
    Middleware.Response.success('list')
  );

  /**
   * @apiGroup Review
   * @apiVersion 1.0.0
   * @api {post} /v1/reviews  Create new review
   * @apiDescription Create new review
   * @apiUse authRequest
   * @apiParam {String}   userId escort id
   * @apiParam {String}   rating Score for rate. from 1-5
   * @apiParam {String}   comment
   * @apiPermission superadmin
   */
  router.post(
    '/v1/reviews',
    Middleware.isAuthenticated,
    reviewController.create,
    Middleware.Response.success('review')
  );

  /**
   * @apiGroup Review
   * @apiVersion 1.0.0
   * @api {put} /v1/reviews/:reviewId  Update a review
   * @apiDescription Update a review
   * @apiUse authRequest
   * @apiParam {String}   reviewId        Review id
   * @apiParam {String}   rating Score for rate. from 1-5
   * @apiParam {String}   comment
   * @apiPermission superadmin
   */
  router.put(
    '/v1/reviews/:reviewId',
    Middleware.isAuthenticated,
    reviewController.findOne,
    reviewController.update,
    Middleware.Response.success('update')
  );

  /**
   * @apiGroup Review
   * @apiVersion 1.0.0
   * @api {delete} /v1/reviews/:reviewId Remove a review
   * @apiDescription Remove a review
   * @apiUse authRequest
   * @apiParam {String}   reviewId        Review id
   * @apiPermission superadmin
   */
  router.delete(
    '/v1/reviews/:reviewId',
    Middleware.isAuthenticated,
    reviewController.findOne,
    reviewController.remove,
    Middleware.Response.success('remove')
  );

  /**
   * @apiGroup Review
   * @apiVersion 1.0.0
   * @api {get} /v1/reviews/:reviewId Get review details
   * @apiDescription Get review details
   * @apiParam {String}   reviewId        Review id
   * @apiPermission all
   */
  router.get(
    '/v1/reviews/:reviewId',
    reviewController.findOne,
    Middleware.Response.success('review')
  );
};
