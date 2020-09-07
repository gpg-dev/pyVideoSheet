exports.mongoosePlugin = require('./mongoosePlugin');

exports.services = {
  Escort: require('./services/escort')
};

exports.router = (router) => {
  require('./routes/escort.route')(router);
};

exports.agendaJobs = [{
  name: 'update-expired-escort-featured',
  interval: '1 day',
  job: async (job, done) => {
    try {
      await DB.User.updateMany({
        type: 'escort',
        featuredTo: {
          $lt: new Date()
        }
      }, {
        $set: { featured: false }
      });
    } catch (e) {
      done();
    }
  }
}];
