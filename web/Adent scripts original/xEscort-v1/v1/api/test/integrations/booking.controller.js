describe('Test booking module', () => {
  let booking;
  const newBooking = {
    name: 'user name 1',
    email: 'testing@test.com',
    phoneNumber: '13245',
    city: 'Some city',
    date: '2018-01-01',
    startTime: 420,
    duration: '30m',
    preferredBy: 'some user',
    clothingStyle: 'sexy',
    message: 'some text'
  };

  before(() => {
    newBooking.escortId = global.user._id;
  });

  it('Should create new booking with user role', async () => {
    const body = await testUtil.request('post', '/v1/booking', adminToken, newBooking);

    expect(body).to.exist;
    expect(body.name).to.equal(newBooking.name);
    booking = body;
  });

  describe('Test get list awaiting-payment booking', () => {
    it('Should not list booking of awaiting-payment', async () => {
      const body = await testUtil.request('get', '/v1/booking', adminToken);

      expect(body).to.exist;
      expect(body.count).to.exist;
      expect(body.items).to.exist;
      expect(body.items).to.be.length(0);
    });
  });

  describe('Test get list booking', () => {
    before(async () => {
      await DB.Booking.updateMany({}, { $set: { status: 'paid' } });
    });

    it('Should not list booking of awaiting-payment', async () => {
      const body = await testUtil.request('get', '/v1/booking', adminToken);

      expect(body).to.exist;
      expect(body.count).to.exist;
      expect(body.items).to.exist;
      expect(body.items).to.be.length(1);
    });

    it('Should get booking detail', async () => {
      const body = await testUtil.request('get', `/v1/booking/${booking._id}`, adminToken);
      expect(body).to.exist;
      expect(body.user).to.exist;
      expect(body.escort).to.exist;
    });
  });
});
