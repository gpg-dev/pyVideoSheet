/* eslint prefer-arrow-callback: 0 */
exports.User = (schema) => {
  schema.add({
    dialCode: {
      type: String
    },
    featured: {
      type: Boolean,
      default: false
    },
    featuredTo: {
      type: Date
    },
    firstName: {
      type: String
    },
    lastName: {
      type: String
    },
    gender: {
      type: String,
      index: true
      // TODO - define enum value?
    },
    orientation: {
      type: String,
      index: true
    },
    age: {
      type: Number
    },
    // https://github.com/umpirsky/language-list/blob/master/data/en/language.json
    languages: {
      type: Array,
      default: []
    },
    serviceFor: {
      type: String,
      index: true
      // TODO - define me
    },
    country: {
      type: String
    },
    baseCity: {
      type: String
    },
    rates: [{
      duration: String,
      price: Number,
      text: String,
      _id: false
    }],
    availableTravel: {
      type: Boolean,
      default: true
    },
    availableTime: {
      // store 0 - 6 (mean Sunday to Saturday)
      0: {
        available: {
          type: Boolean,
          default: true
        },
        // in minute
        from: { type: Number, default: 0 },
        to: { type: Number, default: 1440 } // 12h
      },
      1: {
        available: {
          type: Boolean,
          default: true
        },
        // in minute
        from: { type: Number, default: 0 },
        to: { type: Number, default: 1440 } // 12h
      },
      2: {
        available: {
          type: Boolean,
          default: true
        },
        // in minute
        from: { type: Number, default: 0 },
        to: { type: Number, default: 1440 } // 12h
      },
      3: {
        available: {
          type: Boolean,
          default: true
        },
        // in minute
        from: { type: Number, default: 0 },
        to: { type: Number, default: 1440 } // 12h
      },
      4: {
        available: {
          type: Boolean,
          default: true
        },
        // in minute
        from: { type: Number, default: 0 },
        to: { type: Number, default: 1440 } // 12h
      },
      5: {
        available: {
          type: Boolean,
          default: true
        },
        // in minute
        from: { type: Number, default: 0 },
        to: { type: Number, default: 1440 } // 12h
      },
      6: {
        available: {
          type: Boolean,
          default: true
        },
        // in minute
        from: { type: Number, default: 0 },
        to: { type: Number, default: 1440 } // 12h
      }
    },
    minRate: {
      type: Number
    },
    maxRate: {
      type: Number
    },
    realPics: {
      type: Boolean,
      default: false
    },
    verifiedContact: {
      type: Boolean,
      default: false
    },
    withVideo: {
      type: Boolean,
      default: false
    },
    pornstar: {
      type: Boolean,
      default: false
    },
    naturalPhoto: {
      type: Boolean,
      default: false
    }
  });

  schema.pre('save', function beforeSave(next) {
    if (this.rates && this.rates.length) {
      let min = this.rates[0].price;
      let max = this.rates[0].price;
      for (let i = 1; i < this.rates.length; i++) {
        if (this.rates[i].price < min && this.rates[i].price > 0) {
          min = this.rates[i].price;
        }

        if (this.rates[i].price > max && this.rates[i].price > 0) {
          max = this.rates[i].price;
        }
      }

      this.minRate = min;
      this.maxRate = max;
    }

    next();
  });
};
