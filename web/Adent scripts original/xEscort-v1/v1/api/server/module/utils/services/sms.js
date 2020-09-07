/* Twilio sms */
const twilio = require('twilio');
const nconf = require('nconf');
const Queue = require('bee-queue');

const smsQ = new Queue('sms');

const client = new twilio(nconf.get('SMS_SID'), nconf.get('SMS_AUTH_TOKEN'));

exports.send = body => smsQ.createJob(body).save();

smsQ.process(async (job, done) => {
  try {
    const body = job.data;
    await client.messages.create({
      body: body.text,
      to: body.to, // Text this number
      from: nconf.get('SMS_FROM') // From a valid Twilio number
    });
  } catch (e) {
    await Service.Logger.create({
      level: 'error',
      error: e,
      path: 'sms',
      req: {
        body
      }
    });
  }

  done();
});
