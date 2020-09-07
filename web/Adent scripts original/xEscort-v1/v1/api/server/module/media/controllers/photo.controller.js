const Joi = require('joi');
const Image = require('../components/image');

const resizeOption = {
  thumb: {
    w: 270,
    h: 260
  },
  medium: {
    w: 600,
    h: 600
  }
};

exports.base64Upload = async (req, res, next) => {
  try {
    if (!req.body.base64) {
      return next();
    }

    const data = await Image.saveBase64Image(req.body.base64, req.body);
    req.base64Photo = data;
    return next();
  } catch (e) {
    throw e;
  }
};

/**
 * do upload a photo
 */
exports.upload = async (req, res, next) => {
  try {
    if (!req.file && !req.base64Photo) {
      return next(PopulateResponse.error({
        message: 'Missing photo file!'
      }, 'ERR_MISSING_PHOTO'));
    }

    const file = req.file || req.base64Photo;
    const schema = Joi.object().keys({
      name: Joi.string().allow(['', null]).optional(),
      description: Joi.string().allow(['', null]).optional(),
      categoryIds: Joi.array().items(Joi.string()).optional().default([]),
      systemType: Joi.string().allow(['', null]).optional()
    }).unknown();

    const validate = Joi.validate(req.body, schema);
    if (validate.error) {
      return next(PopulateResponse.validationError(validate.error));
    }

    const thumbPath = await Image.resize({
      input: file.path,
      width: resizeOption.thumb.w,
      height: resizeOption.thumb.h
    });
    const mediumPath = await Image.resize({
      input: file.path,
      width: resizeOption.medium.w,
      height: resizeOption.medium.h
    });
    const photo = new DB.Media({
      type: 'photo',
      systemType: validate.value.systemType,
      name: validate.value.name || file.filename,
      mimeType: file.mimetype,
      description: validate.value.description,
      uploaderId: req.user._id,
      ownerId: req.user.role === 'admin' && req.body.ownerId ? req.body.ownerId : req.user._id,
      categoryIds: validate.value.categoryIds,
      originalPath: file.path,
      filePath: file.path,
      thumbPath,
      mediumPath,
      convertStatus: 'done'
    });
    await photo.save();

    res.locals.photo = photo;
    return next();
  } catch (e) {
    return next(e);
  }
};
