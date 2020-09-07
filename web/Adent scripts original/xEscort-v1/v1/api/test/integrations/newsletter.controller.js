describe('Test newsletter module', () => {
  it('Should send newsletter to all users', async () => {
    const body = await testUtil.request('post', '/v1/newsletter/sendmail', global.adminToken, {
      subject: 'Testing newsletter',
      content: '<strong>Some text</strong>'
    });

    expect(body).to.exist;
    expect(body.success).to.equal(true);
  });

  it('Should register newsletter contact', async () => {
    const body = await testUtil.request('post', '/v1/newsletter/contact', null, {
      email: 'testing123@yopmail.com',
      name: 'Testing'
    });

    expect(body).to.exist;
    expect(body.success).to.equal(true);
  });
});
