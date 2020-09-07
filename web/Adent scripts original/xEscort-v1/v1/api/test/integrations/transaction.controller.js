describe('Test payment', () => {
  it('Should able to request transactions', async () => {
    const body = await testUtil.request('post', '/v1/payment/transactions/request', adminToken, {
      gateway: 'paypal',
      service: 'escort_subscription',
      redirectSuccessUrl: 'http://localhost',
      redirectCancelUrl: 'http://localhost'
    });

    expect(body).to.exist;
    expect(body.redirectUrl).to.exist;
  });
});
