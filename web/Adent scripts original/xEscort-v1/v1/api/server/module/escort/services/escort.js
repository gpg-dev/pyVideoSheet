/* eslint no-param-reassign: 0, no-return-assign: 0 */
exports.hidePrivateData = (data) => {
  try {
    const escorts = Array.isArray(data) ? data : [data];
    const privateFields = ['phoneNumber', 'phoneVerified', 'address', 'dialCode', 'email'];
    escorts.forEach(escort => privateFields.forEach(field => escort[field] = '*****'));
  } catch (e) {
    throw e;
  }
};
