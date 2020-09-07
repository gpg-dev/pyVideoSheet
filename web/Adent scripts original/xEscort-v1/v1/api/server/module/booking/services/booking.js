const moment = require('moment');

function convertMinsToHrsMins(mins) {
  let h = Math.floor(mins / 60);
  let m = mins % 60;
  h = h < 10 ? `0${h}` : h;
  m = m < 10 ? `0${m}` : m;
  return `${h}:${m}`;
}

exports.updateBookingSuccess = async (bookingId) => {
  try {
    const booking = await DB.Booking.findOne({ _id: bookingId });
    if (!booking) {
      throw new Error('Booking cannot found!');
    }

    booking.status = 'paid';
    await booking.save();
    const date = moment(booking.date).format('DD/MM/YYYY');
    const time = convertMinsToHrsMins(booking.startTime);
    const escort = await DB.User.findOne({ _id: booking.escortId });
    const user = await DB.User.findOne({ _id: booking.userId });

    Service.Mailer.send('booking/new-booking-confirm-to-escort.html', escort.email, {
      subject: `New booking on ${date} ${time}`,
      booking: booking.toObject(),
      escort: escort.toObject(),
      user: user.toObject(),
      date,
      time
    });
    Service.Mailer.send('booking/new-booking-confirm-to-user.html', user.email, {
      subject: `Confirmation: Booking ${escort.username || escort.name} on ${date} ${time}`,
      booking: booking.toObject(),
      escort: escort.toObject(),
      user: user.toObject(),
      date,
      time
    });

    return true;
  } catch (e) {
    throw e;
  }
};
