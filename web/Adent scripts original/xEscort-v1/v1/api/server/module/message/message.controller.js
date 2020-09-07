const Joi = require('joi');

exports.sendMessage = async (req, res, next) => {
  try {
    const schema = Joi.object().keys({
      escortId: Joi.string().required(),
      content: Joi.string().required()
    });

    const validate = Joi.validate(req.body, schema);
    if (validate.error) {
      return next(PopulateResponse.validationError(validate.error));
    }

    const escort = await DB.User.findOne({ _id: req.body.escortId, isActive: true, type: 'escort' });
    if (!escort) {
      return next(PopulateResponse.notFound());
    }

    // TODO - log me
    const subject = 'New message';
    Service.Mailer.send('escort-direct-message.html', escort.email, {
      subject,
      user: req.user.toObject(),
      message: {
        content: req.body.content
      }
    });

    const log = new DB.MessageLog({
      senderId: req.user._id,
      receiverId: escort._id,
      subject,
      content: req.body.content
    });
    await log.save();

    res.locals.message = {
      message: 'Message has sent.'
    };
    return next();
  } catch (e) {
    return next(e);
  }
};
