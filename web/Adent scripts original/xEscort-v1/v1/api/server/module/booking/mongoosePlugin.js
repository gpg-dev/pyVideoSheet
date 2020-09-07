/* eslint prefer-arrow-callback: 0 */
exports.User = (schema) => {
  schema.post('remove', async function afterRemove(doc, next) {
    await DB.Booking.remove({
      $or: [{
        userId: doc._id
      }, {
        escortId: doc._id
      }]
    });

    next();
  });
};
