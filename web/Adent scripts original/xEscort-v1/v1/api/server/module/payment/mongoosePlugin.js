/* eslint prefer-arrow-callback: 0 */
exports.User = (schema) => {
  schema.add({
    subscribed: {
      type: Boolean,
      default: false
    },
    subscribeTo: {
      type: Date
    }
  });

  schema.post('remove', async function afterRemove(doc, next) {
    await DB.Transaction.remove({ userId: doc._id });
    await DB.Invoice.remove({ userId: doc._id });

    next();
  });
};
