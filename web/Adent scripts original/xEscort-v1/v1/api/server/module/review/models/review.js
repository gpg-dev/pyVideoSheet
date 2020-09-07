const Schema = require('mongoose').Schema;

const schema = new Schema({
  userId: {
    type: Schema.Types.ObjectId,
    ref: 'User',
    index: true
  },
  rateBy: {
    type: Schema.Types.ObjectId,
    ref: 'User',
    index: true
  },
  comment: {
    type: String
  },
  rating: {
    type: Number
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

schema.virtual('rater', {
  ref: 'User',
  localField: 'rateBy',
  foreignField: '_id',
  justOne: true
});

module.exports = schema;
