const Joi = require('joi');
const nconf = require('nconf');
const url = require('url');

exports.register = async (req, res, next) => {
  const schema = Joi.object().keys({
    type: Joi.string().allow(['user', 'escort']).default('user'),
    username: Joi.string().required(),
    email: Joi.string().email().required(),
    password: Joi.string().min(6).required(),
    dialCode: Joi.string().allow(['', null]).optional(),
    phoneNumber: Joi.string().allow(['', null]).optional(),
    name: Joi.string().allow(['', null]).optional()
  });

  const validate = Joi.validate(req.body, schema);
  if (validate.error) {
    return next(PopulateResponse.validationError(validate.error));
  }

  try {
    const count = await DB.User.count({
      email: validate.value.email.toLowerCase()
    });
    if (count) {
      return next(PopulateResponse.error({
        message: 'This email has already taken'
      }, 'ERR_EMAIL_ALREADY_TAKEN'));
    }

    const usernameCheck = await DB.User.count({
      username: validate.value.username.toLowerCase()
    });
    if (usernameCheck) {
      return next(PopulateResponse.error({
        message: 'This username has already taken'
      }, 'ERR_USERNAME_ALREADY_TAKEN'));
    }

    const user = new DB.User(validate.value);
    user.emailVerifiedToken = Helper.String.randomString(48);
    await user.save();

    // now send email verificaiton to user
    Service.Mailer.send('verify-email.html', user.email, {
      subject: 'Verify email address',
      emailVerifyLink: url.resolve(nconf.get('baseUrl'), `v1/auth/verifyEmail/${user.emailVerifiedToken}`)
    });

    if (user.type === 'escort' && user.phoneNumber) {
      const code = Helper.String.randomString(5).toUpperCase();
      let phoneVerify = await DB.PhoneVerify.findOne({
        phoneNumber: user.phoneNumber
      });
      if (!phoneVerify) {
        phoneVerify = new DB.PhoneVerify({
          userId: user._id,
          phoneNumber: user.phoneNumber,
          dialCode: req.body.dialCode
        });
      }
      phoneVerify.code = code;
      await phoneVerify.save();
      await Service.Sms.send({
        text: `Your verification code is: ${code}`,
        to: user.phoneNumber
      });
    }

    res.locals.register = PopulateResponse.success({
      message: 'Your account has been created, please verify your email address and get access.',
      _id: user._id
    }, 'USE_CREATED');
    return next();
  } catch (e) {
    return next(e);
  }
};

exports.verifyEmail = async (req, res, next) => {
  const schema = Joi.object().keys({
    token: Joi.string().required()
  });

  const validate = Joi.validate(req.body, schema);
  if (validate.error) {
    return next(PopulateResponse.validationError(validate.error));
  }

  try {
    const user = await DB.User.findOne({
      emailVerifiedToken: req.body.token
    });
    if (!user) {
      return next(PopulateResponse.error({
        message: 'This token is incorrect'
      }, 'ERR_INVALID_EMAIL_VERIFY_TOKEN'));
    }

    user.emailVerified = true;
    user.emailVerifiedToken = null;
    await user.save();

    res.locals.verifyEmail = PopulateResponse.success({
      message: 'Your email has been verified.'
    }, 'EMAIL_VERIFIED');
    return next();
  } catch (e) {
    return next(e);
  }
};

exports.verifyEmailView = async (req, res, next) => {
  try {
    const user = await DB.User.findOne({
      emailVerifiedToken: req.params.token
    });

    if (user) {
      user.emailVerified = true;
      user.emailVerifiedToken = null;
      await user.save();
    }

    return res.render('auth/verify-email.html', {
      verified: user !== null,
      siteName: nconf.get('SITE_NAME')
    });
  } catch (e) {
    return next(e);
  }
};

exports.forgot = async (req, res, next) => {
  const schema = Joi.object().keys({
    email: Joi.string().email().required()
  });

  const validate = Joi.validate(req.body, schema);
  if (validate.error) {
    return next(PopulateResponse.validationError(validate.error));
  }

  try {
    const user = await DB.User.findOne({
      email: validate.value.email
    });
    if (!user) {
      return next(PopulateResponse.error({
        message: 'This email is not registered'
      }, 'ERR_INVALID_EMAIL_ADDRESS'));
    }

    const passwordResetToken = Helper.String.randomString(48);
    await DB.User.update({
      _id: user._id
    }, {
      $set: { passwordResetToken }
    });

    // now send email verificaiton to user
    Service.Mailer.send('forgot-password.html', user.email, {
      subject: 'Forgot password',
      passwordResetLink: url.resolve(nconf.get('baseUrl'), `v1/auth/passwordReset/${passwordResetToken}`),
      user: user.toObject()
    });

    res.locals.forgot = PopulateResponse.success({
      message: 'Your password email has been sent.'
    }, 'FORGOT_PASSWORD_EMAIL_SENT');
    return next();
  } catch (e) {
    return next(e);
  }
};

exports.resetPasswordView = async (req, res, next) => {
  try {
    const user = await DB.User.findOne({
      passwordResetToken: req.params.token
    });

    if (!user) {
      return res.render('not-found.html');
    }

    if (req.method === 'GET') {
      return res.render('auth/change-password.html', {
        openForm: true
      });
    }

    if (!req.body.password) {
      return res.render('auth/change-password.html', {
        openForm: true,
        error: true,
        siteName: nconf.get('SITE_NAME')
      });
    }

    user.password = req.body.password;
    user.passwordResetToken = null;
    await user.save();

    return res.render('auth/change-password.html', {
      openForm: false,
      error: false,
      siteName: nconf.get('SITE_NAME')
    });
  } catch (e) {
    return next(e);
  }
};
