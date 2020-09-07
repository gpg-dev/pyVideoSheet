const messageController = require('./message.controller');

exports.model = {
  MessageLog: require('./models/message-log')
};

exports.router = (router) => {
  /**
   * @apiGroup Escort
   * @apiVersion 1.0.0
   * @api {post} /messages  Send message to escort
   * @apiHeader {String}    Authorization       Authorization token
   * @apiParam {String}   escortId escort id
   * @apiParam {String}   content message content
   *
   * @apiPermission user
   */
  router.post(
    '/v1/messages',
    Middleware.isAuthenticated,
    messageController.sendMessage,
    Middleware.Response.success('message')
  );
};
