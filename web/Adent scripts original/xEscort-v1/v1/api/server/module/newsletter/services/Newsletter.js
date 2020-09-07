/* eslint prefer-arrow-callback: 0 */

const async = require('async');
const Queue = require('../../../kernel/services/queue');

const newsletterQ = Queue.create('newsletter');

newsletterQ.process(async (job, done) => {
  try {
    const data = job.data;
    const query = {};
    if (data.userType) {
      query.type = data.userType;
    }

    const count = 0;
    const limit = 10;
    const totalUser = await DB.User.count(query);
    if (!totalUser) {
      return done();
    }

    async.during(
      function check(cb) {
        return cb(null, totalUser > count);
      },
      async function doFunction(cb) {
        const users = await DB.User.find(query).skip(count).limit(limit);
        await Promise.all(users.map(user => Service.Mailer.send('newsletter/default.html', user.email, {
          subject: data.subject,
          // TODO - filter content like replace username, etc...
          content: data.content
        })));

        cb();
      },
      function executed() {
        done();
      }
    );
  } catch (e) {
    return done();
  }
});

exports.sendMail = async (data) => {
  try {
    return newsletterQ.createJob(data).save();
  } catch (e) {
    throw e;
  }
};
