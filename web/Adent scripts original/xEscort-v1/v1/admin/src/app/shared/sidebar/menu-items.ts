import { RouteInfo } from './sidebar.metadata';

export const ROUTES: RouteInfo[] = [
  {
    path: '', title: 'Personal', icon: '', class: 'nav-small-cap', label: '', labelClass: '', extralink: true, submenu: []
  },
  {
    path: '/starter', title: 'Starter Page', icon: 'mdi mdi-gauge', class: '', label: '', labelClass: '', extralink: false, submenu: []
  },
  {
    path: '/users/list', title: 'Users', icon: 'fa fa-users', class: 'has-arrow', label: '', labelClass: '', extralink: false,
    submenu: [
      { path: '/users/list', title: 'List users', icon: 'fa fa-users', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
      { path: '/users/create', title: 'Create new', icon: 'fa fa-file', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
    ]
  },
  {
    path: '/escorts/list', title: 'Escorts', icon: 'fa fa-star', class: 'has-arrow', label: '', labelClass: '', extralink: false,
    submenu: [
      { path: '/escorts/list', title: 'List escort', icon: 'fa fa-star', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
      { path: '/escorts/create', title: 'New escort', icon: 'fa fa-file', class: '', label: '', labelClass: '', extralink: false, submenu: [] }
    ]
  },
  {
    path: '/booking/list', title: 'Booking', icon: 'fa fa-star', class: 'has-arrow', label: '', labelClass: '', extralink: false,
    submenu: [
      { path: '/booking/list', title: 'List booking', icon: 'fa fa-star', class: '', label: '', labelClass: '', extralink: false, submenu: [] }
    ]
  },
  {
    path: '/invoices/list', title: 'Invoices', icon: 'fa fa-star', class: 'has-arrow', label: '', labelClass: '', extralink: false,
    submenu: [
      { path: '/invoices/list', title: 'List invoices', icon: 'fa fa-star', class: '', label: '', labelClass: '', extralink: false, submenu: [] }
    ]
  },
  {
    path: '/configs', title: 'Config', icon: 'fa fa-cogs', class: '', label: '', labelClass: '', extralink: false, submenu: [
      { path: '/configs', title: 'Config', icon: 'fa fa-cogs', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
      {
        path: '/i18n/languages', title: 'I18n', icon: 'fa fa-flag', class: '', label: '', labelClass: '', extralink: false, submenu: [
          { path: '/i18n/languages', title: 'Languages', icon: 'fa fa-flag', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
          { path: '/i18n/text', title: 'Text', icon: 'fa fa-font', class: '', label: '', labelClass: '', extralink: false, submenu: [] }
        ]
      }
    ]
  }
];
