const Joi = require('joi');
const _ = require('lodash');

exports.register = async (req, res, next) => {
  try {
    const validateSchema = Joi.object().keys({
      email: Joi.string().required(),
      name: Joi.string().allow([null, '']).optional(),
      address: Joi.string().allow([null, '']).optional()
    });
    const validate = Joi.validate(req.body, validateSchema);
    if (validate.error) {
      return next(PopulateResponse.validationError(validate.error));
    }

    let contact = await DB.Contact.findOne({ email: validate.value.email });
    if (!contact) {
      contact = new DB.Contact(validate.value);
    }

    _.merge(contact, validate.value);
    await contact.save();
    res.locals.register = { success: true };
    return next();
  } catch (e) {
    return next(e);
  }
};
