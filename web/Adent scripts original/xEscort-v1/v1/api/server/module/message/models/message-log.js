const Schema = require('mongoose').Schema;

const schema = new Schema({
  senderId: {
    type: Schema.Types.ObjectId
  },
  receiverId: {
    type: Schema.Types.ObjectId,
    index: true
  },
  subject: {
    type: String
  },
  content: {
    type: String
  },
  createdAt: {
    type: Date
  },
  updatedAt: {
    type: Date
  }
});

module.exports = schema;
