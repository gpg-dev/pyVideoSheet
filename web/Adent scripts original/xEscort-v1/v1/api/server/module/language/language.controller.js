const languageObj = require('./languages');

exports.list = (req, res, next) => {
  const languages = Object.keys(languageObj)
    .map(key => ({
      id: key,
      name: languageObj[key]
    }));
  res.locals.languages = languages;
  next();
};