const Schema = require('mongoose').Schema;

const schema = new Schema({
  userId: {
    type: Schema.Types.ObjectId,
    ref: 'User'
  },
  escortId: {
    type: Schema.Types.ObjectId,
    ref: 'User'
  },
  name: {
    type: String
  },
  email: {
    type: String
  },
  phoneNumber: {
    type: String
  },
  city: {
    type: String
  },
  date: {
    type: Date
  },
  startTime: {
    type: Number
  },
  duration: {
    type: String
  },
  preferredBy: {
    type: String
  },
  clothingStyle: {
    type: String
  },
  message: {
    type: String
  },
  status: {
    type: String,
    // enum: ['wating-payment']
    default: 'waiting-payment',
    index: true
  },
  createdAt: {
    type: Date
  },
  updatedAt: {
    type: Date
  }
}, {
  timestamps: {
    createdAt: 'createdAt',
    updatedAt: 'updatedAt'
  },
  toJSON: {
    virtuals: true
  },
  toObject: {
    virtuals: true
  }
});

schema.virtual('escort', {
  ref: 'User',
  localField: 'escortId',
  foreignField: '_id',
  justOne: true
});

schema.virtual('user', {
  ref: 'User',
  localField: 'userId',
  foreignField: '_id',
  justOne: true
});

module.exports = schema;
