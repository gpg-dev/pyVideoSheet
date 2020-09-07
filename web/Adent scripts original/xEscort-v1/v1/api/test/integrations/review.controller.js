describe('Test review module', () => {
  let review;
  const newReview = {
    rating: 5,
    comment: 'cool'
  };
  const newComment = 'Post name 2';

  before(() => {
    newReview.userId = global.user._id;
  });

  it('Should create new review', async () => {
    const body = await testUtil.request('post', '/v1/reviews', adminToken, newReview);

    expect(body).to.exist;
    expect(body.rating).to.equal(newReview.rating);
    review = body;
  });

  it('Should update review', async () => {
    const body = await testUtil.request('put', `/v1/reviews/${review._id}`, adminToken, { comment: newComment });

    expect(body).to.exist;
    expect(body.comment).to.equal(newComment);
  });

  it('Should get review detail', async () => {
    const body = await testUtil.request('get', `/v1/reviews/${review._id}`);

    expect(body).to.exist;
    expect(body.rater).to.exist;
  });

  it('Should list reviews', async () => {
    const body = await testUtil.request('get', '/v1/reviews');

    expect(body).to.exist;
    expect(body.count).to.exist;
    expect(body.items).to.exist;
  });
  
  it('Should have rating data for user', async () => {
    const user = await DB.User.findOne({ _id: global.user._id });

    expect(user).to.exist;
    expect(user.ratingAvg).to.exist;
    expect(user.totalRating).to.equal(1);
    expect(user.ratingScore).to.equal(5);
  });
});
