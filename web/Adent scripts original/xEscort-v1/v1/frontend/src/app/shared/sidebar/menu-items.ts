import { RouteInfo } from './sidebar.metadata';

export const ROUTES: RouteInfo[] = [
  {
    path: '/starter', title: 'Dashboard', icon: 'fa fa-dashboard', class: '', label: '', labelClass: '', extralink: false, submenu: []
  },
  {
    path: '/users/list', title: 'Users', icon: 'fa fa-users', class: 'has-arrow', label: '', labelClass: '', extralink: false,
    submenu: [
      { path: '/users/list', title: 'List users', icon: 'fa fa-users', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
      { path: '/users/create', title: 'Create new', icon: 'fa fa fa-user-plus', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
    ]
  },
  {
    path: '/users/groups', title: 'Groups', icon: 'fa fa-object-group', class: 'has-arrow', label: '', labelClass: '', extralink: false, submenu: [
      { path: '/users/groups', title: 'List groups', icon: 'fa fa-object-group', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
      { path: '/users/groups/create', title: 'Create new', icon: 'fa fa-file', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
    ]
  },
  {
    path: '/products/brands', title: 'Brands', icon: 'fa fa-university', class: 'has-arrow', label: '', labelClass: '', extralink: false, submenu: [
      { path: '/products/brands', title: 'brands', icon: 'fa fa-university', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
      { path: '/products/brands/create', title: 'New brand', icon: 'fa fa-plus', class: '', label: '', labelClass: '', extralink: false, submenu: [] }
    ]
  },
  {
    path: '/products', title: 'Products', icon: 'fa fa-database', class: 'has-arrow', label: '', labelClass: '', extralink: false, submenu: [
      { path: '/products', title: 'Products', icon: 'fa fa-database', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
      { path: '/products/create', title: 'New product', icon: 'fa fa-plus', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
      { path: '/products/categories', title: 'Products categories', icon: 'fa fa-cubes', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
      { path: '/products/categories/create', title: 'Create new category', icon: 'fa fa-plus', class: '', label: '', labelClass: '', extralink: false, submenu: [] },
    ]
  },
  {
    path: '/products/catagories-groups', title: 'Category/groups', icon: 'fa fa-dashboard', class: '', label: '', labelClass: '', extralink: false, submenu: []
  },
  {
    path: '/configs', title: 'Config', icon: 'fa fa-cogs', class: '', label: '', labelClass: '', extralink: false, submenu: []
  }
];
