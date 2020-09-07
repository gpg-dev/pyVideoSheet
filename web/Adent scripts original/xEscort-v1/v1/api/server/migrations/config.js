module.exports = async () => DB.Config.find({})
  .remove()
  .then(() => DB.Config.create({
    key: 'siteName',
    value: 'Escort scripting',
    name: 'Site name',
    public: true
  }, {
    key: 'siteLogo',
    value: '/assets/images/logo.png',
    name: 'Site logo',
    public: true
  }, {
    key: 'siteLogoHome',
    value: '/assets/images/logo.png',
    name: 'Home logo',
    public: true
  }, {
    key: 'siteFavicon',
    value: '/favicon.ico',
    name: 'Site favicon',
    public: true
  }, {
    key: 'contactEmail',
    value: 'admin@example.com',
    name: 'Contact email',
    public: false
  }, {
    key: 'homeSEO',
    value: {
      keywords: '',
      description: ''
    },
    name: 'SEO meta data for home page',
    type: 'mixed',
    public: true
  }, {
    key: 'codeHead',
    value: '',
    name: 'Custom code before end head tag',
    public: true
  }, {
    key: 'codeBody',
    value: '',
    name: 'Custom code before end body tag',
    public: true
  }, {
    key: 'paypal',
    type: 'mixed',
    value: {
      mode: 'sandbox',
      client_id: 'AcBqoxAt02lIEP1MkHLV8HVnVOv0Rffx_R3TsQ49BXGBLX8NzEILBzLW5EVl',
      client_secret: 'ENjHvhAKMWMYE-b5Uy3gAm31AZgF97-j03I11a8qL14rgEHOqfvTEnqYjU4V'
    },
    name: 'Paypal payment config',
    public: false
  }, {
    key: 'subscriptionEscortPrice',
    value: 10,
    name: 'Price for escort recurring subscription',
    public: true,
    type: 'number'
  }, {
    key: 'subscriptionUserPrice',
    value: 10,
    name: 'Price for user recurring subscription',
    public: true,
    type: 'number'
  }, {
    key: 'bookingPrice',
    value: 5,
    name: 'Booking price for each booking request',
    public: true,
    type: 'number'
  }, {
    key: 'aboutFooter',
    value: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries",
    name: 'About us footer',
    public: true,
    type: 'text'
  }, {
    key: 'facebookLink',
    value: 'https://facebook.com',
    name: 'Facebook link',
    public: true,
    type: 'text'
  }, {
    key: 'twitterLink',
    value: 'https://twitter.com',
    name: 'Twitter link',
    public: true,
    type: 'text'
  }, {
    key: 'linkedinLink',
    value: 'https://linkedin.com',
    name: 'Linkedin link',
    public: true,
    type: 'text'
  }, {
    key: 'address',
    value: '1234 Rosecrans Ave, Suite 100 El Segundo, CA 12345. USA',
    name: 'Address',
    public: true,
    type: 'text'
  }, {
    key: 'hotline',
    value: '0012300000',
    name: 'Hotline number',
    public: true,
    type: 'text'
  }, {
    key: 'contactEmail',
    value: 'admin@xescort.com',
    name: 'Contact email',
    public: true,
    type: 'text'
  }));
