/* eslint prefer-arrow-callback: 0 */
exports.User = (schema) => {
  schema.add({
    ratingAvg: {
      type: Number,
      default: 0
    },
    totalRating: {
      type: Number,
      default: 0
    },
    ratingScore: {
      type: Number,
      default: 0
    }
  });

  schema.post('remove', async function afterRemove(doc, next) {
    await DB.Review.remove({
      $or: [{
        userId: doc._id
      }, {
        rateBy: doc._id
      }]
    });

    next();
  });
};
