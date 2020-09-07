/* eslint no-param-reassign: 0 */
// handle all payment data from other paymentGateway
const Paypal = require('./Paypal');
const moment = require('moment');

async function extendEscortFeaturedSubscription(escortId) {
  try {
    const escort = await DB.User.findOne({ _id: escortId });
    if (!escort) {
      throw new Error('Escort not found');
    }

    escort.featured = true;
    escort.featuredTo = moment().add(1, 'month').toDate();
    return await escort.save();
  } catch (e) {
    throw e;
  }
}

async function extendUserSubscribeSubscription(userId) {
  try {
    const user = await DB.User.findOne({ _id: userId });
    if (!user) {
      throw new Error('User not found');
    }

    // TODO - should use update function?
    user.subscribed = true;
    user.subscribeTo = moment().add(1, 'month').toDate();
    return await user.save();
  } catch (e) {
    throw e;
  }
}

async function updateBookingCompletedStatus(bookingId) {
  try {
    return Service.Booking.updateBookingSuccess(bookingId);
  } catch (e) {
    throw e;
  }
}

exports.createSubscriptionTransaction = async (options) => {
  try {
    if (options.gateway !== 'paypal') {
      throw new Error('Not support gateway now!');
    }

    const paymentOptions = options;
    const key = options.subscriptionType;
    const paypalConfig = await DB.Config.findOne({ key: 'paypal' });
    paymentOptions.config = paypalConfig ? paypalConfig.value : AppConfig.paypal;
    let config = await DB.Config.findOne({ key });
    if (!config) {
      const billingPlan = await Paypal.createSubscriptionPlan(key, paymentOptions);
      config = new DB.Config({
        key,
        value: billingPlan,
        name: options.description,
        visible: false
      });
      await config.save();
    }

    const data = await Paypal.createSubscriptionPayment(config.value, paymentOptions);

    const transaction = new DB.Transaction({
      userId: options.userId,
      type: options.subscriptionType,
      price: options.price,
      description: options.description,
      items: [{
        id: options.subscriptionType,
        price: options.price,
        description: options.description
      }],
      paymentGateway: 'paypal',
      paymentId: data.id,
      paymentToken: data.token,
      meta: Object.assign(options.meta, data)
    });

    await transaction.save();
    return {
      redirectUrl: data.links.approval_url
    };
  } catch (e) {
    throw e;
  }
};

exports.executePaypalSubscriptionAgreement = async (paymentToken) => {
  try {
    const transaction = await DB.Transaction.findOne({ paymentToken });
    if (!transaction) {
      throw new Error('Cannot find this transaction');
    }

    const paypalConfig = await DB.Config.findOne({ key: 'paypal' });
    const paymentOptions = {
      config: paypalConfig ? paypalConfig.value : AppConfig.paypal
    };
    const response = await Paypal.billingAgreementSubscription(paymentOptions, paymentToken);

    transaction.status = 'completed';
    transaction.paymentResponse = response;
    transaction.paymentAgreementId = response.id;
    // Log.deep(data);
    return await transaction.save();
  } catch (e) {
    throw e;
  }
};

exports.updatePaypalTransaction = async (body) => {
  try {
    // NOT support for single sale for now, just manage for subscription
    if (!body.resource.billing_agreement_id || body.event_type !== 'PAYMENT.SALE.COMPLETED') {
      return true;
    }
    const transaction = await DB.Transaction.findOne({ paymentAgreementId: body.resource.billing_agreement_id });
    if (!transaction) {
      throw new Error('Transaction not found');
    }

    await DB.Transaction.update({ _id: transaction._id }, {
      $push: {
        histories: body
      }
    });

    // create new invoice for user
    const invoiceData = transaction.toObject();
    delete invoiceData._id;
    const invoice = new DB.Invoice(invoiceData);
    invoice.transactionId = transaction._id;
    await invoice.save();

    const user = await DB.User.findOne({ _id: invoice.userId });
    if (transaction.type === 'escort_subscription') {
      await extendEscortFeaturedSubscription(invoice.userId);
      Service.Mailer.send('payment/featured-escort.html', user.email, {
        subject: 'Monthly featured account subscription',
        escort: user.toObject(),
        invoice: invoice.toObject()
      });
    } else if (transaction.type === 'user_subscription') {
      await extendUserSubscribeSubscription(invoice.userId);
      Service.Mailer.send('payment/subscription-user.html', user.email, {
        subject: 'Monthly subscription for membership',
        user: user.toObject(),
        invoice: invoice.toObject()
      });
    }

    return true;
  } catch (e) {
    throw e;
  }
};

exports.createSinglePayment = async (options) => {
  try {
    if (options.gateway !== 'paypal') {
      throw new Error('Not support gateway now!');
    }

    const paymentOptions = options;
    const paypalConfig = await DB.Config.findOne({ key: 'paypal' });
    paymentOptions.config = paypalConfig ? paypalConfig.value : AppConfig.paypal;
    const data = await Paypal.createSinglePayment(paymentOptions);

    const transaction = new DB.Transaction({
      userId: options.userId,
      type: options.type,
      price: options.price,
      description: options.description,
      items: [{
        id: options.itemId,
        price: options.price,
        description: options.description
      }],
      paymentGateway: 'paypal',
      paymentId: data.id,
      paymentToken: data.token,
      meta: Object.assign(options.meta, data)
    });

    await transaction.save();
    return {
      redirectUrl: data.links.approval_url
    };
  } catch (e) {
    throw e;
  }
};

exports.executeSinglePayment = async (transaction, options) => {
  try {
    if (transaction.paymentGateway !== 'paypal') {
      throw new Error('Not support yet');
    }

    const paypalConfig = await DB.Config.findOne({ key: 'paypal' });
    const paymentOptions = {
      config: paypalConfig ? paypalConfig.value : AppConfig.paypal,
      payerId: options.PayerID,
      price: transaction.price,
      paymentId: transaction.paymentId
    };

    const data = await Paypal.executeSinglePayment(paymentOptions);
    transaction.paymentResponse = data;
    transaction.status = 'completed';
    await transaction.save();

    if (transaction.type === 'booking') {
      await updateBookingCompletedStatus(transaction.items[0].id);
    }

    const invoiceData = transaction.toObject();
    delete invoiceData._id;
    const invoice = new DB.Invoice(invoiceData);
    invoice.transactionId = transaction._id;
    await invoice.save();

    return data;
  } catch (e) {
    throw e;
  }
};
