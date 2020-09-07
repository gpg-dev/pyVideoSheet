const path = require('path');
const nconf = require('nconf');

nconf.argv()
  .env()
  .file({ file: path.resolve(path.join(__dirname, 'config', `${process.env.NODE_ENV}.json`)) });

const Kernel = require('./kernel');

const kernel = new Kernel();

kernel.loadModule(require('./module/system'));
kernel.loadModule(require('./module/user'));
kernel.loadModule(require('./module/passport'));
kernel.loadModule(require('./module/post'));
kernel.loadModule(require('./module/media'));
kernel.loadModule(require('./module/i18n'));
kernel.loadModule(require('./module/newsletter'));
kernel.loadModule(require('./module/banner'));
kernel.loadModule(require('./module/escort'));
kernel.loadModule(require('./module/message'));
kernel.loadModule(require('./module/language'));
kernel.loadModule(require('./module/payment'));
kernel.loadModule(require('./module/booking'));
kernel.loadModule(require('./module/review'));
kernel.loadModule(require('./module/utils'));

// NOTE - compose at last
kernel.compose();

module.exports = kernel;
