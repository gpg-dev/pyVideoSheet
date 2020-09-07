exports.config = {
  paypal: {
    mode: 'sandbox', // sandbox or live
    client_id: 'AcBqoxAt02lIEP1MkHLV8HVnVOv0Rffx_R3TsQ49BXGBLX8NzEILBzLW5EVl',
    client_secret: 'ENjHvhAKMWMYE-b5Uy3gAm31AZgF97-j03I11a8qL14rgEHOqfvTEnqYjU4V'
  }
};

exports.model = {
  Transaction: require('./models/transaction'),
  Invoice: require('./models/invoice')
};

exports.mongoosePlugin = require('./mongoosePlugin');

exports.router = (router) => {
  require('./routes/transaction')(router);
  require('./routes/paypal')(router);
  require('./routes/invoice')(router);
};

exports.agendaJobs = [{
  name: 'update-expired-subscribed-user',
  interval: '1 day',
  job: async (job, done) => {
    try {
      await DB.User.updateMany({
        type: 'user',
        subscribeTo: {
          $lt: new Date()
        }
      }, {
        $set: { subscribed: false }
      });
    } catch (e) {
      done();
    }
  }
}];
