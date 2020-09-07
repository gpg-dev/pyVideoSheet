const Joi = require('joi');
const Queue = require('../../../kernel/services/queue');

const mediaQ = Queue.create('media');

/**
 * do upload a photo
 */
exports.upload = async (req, res, next) => {
  try {
    if (!req.file) {
      return next(PopulateResponse.error({
        message: 'Missing photo file!'
      }, 'ERR_MISSING_PHOTO'));
    }

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

    const video = new DB.Media({
      type: 'video',
      systemType: validate.value.systemType,
      name: validate.value.name || req.file.filename,
      mimeType: req.file.mimetype,
      description: validate.value.description,
      uploaderId: req.user._id,
      ownerId: req.user.role === 'admin' && req.body.ownerId ? req.body.ownerId : req.user._id,
      categoryIds: validate.value.categoryIds,
      filePath: req.file.path,
      originalPath: req.file.path,
      convertStatus: 'processing'
    });
    await video.save();

    // TODO - define me here
    mediaQ.createJob({
      command: 'convert-mp4',
      data: {
        mediaId: video._id,
        filePath: video.filePath
      }
    }).save();

    res.locals.video = video;
    return next();
  } catch (e) {
    return next(e);
  }
};
