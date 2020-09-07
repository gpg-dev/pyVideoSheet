import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { FullComponent } from './layouts/full/full.component';
import { AuthLayoutComponent } from './layouts/auth/auth.component';

import { AuthGuard } from './shared/guard/auth.guard';

export const Approutes: Routes = [
  {
    path: '',
    component: AuthLayoutComponent,
    children: [
      { path: '', redirectTo: '/auth/login', pathMatch: 'full' },
      { path: 'auth', loadChildren: './auth/auth.module#AuthModule' }
    ]
  },
  {
    path: '',
    component: FullComponent,
    canActivate: [AuthGuard],
    children: [
      { path: '', redirectTo: '/auth/login', pathMatch: 'full' },
      { path: 'starter', loadChildren: './starter/starter.module#StarterModule' },
      { path: 'users', loadChildren: './user/user.module#UserModule' },
      { path: 'escorts', loadChildren: './escort/escort.module#EscortModule' },
      { path: 'booking', loadChildren: './booking/booking.module#BookingModule' },
      { path: 'invoices', loadChildren: './invoice/invoice.module#InvoiceModule' },
      { path: 'configs', loadChildren: './config/config.module#ConfigModule' },
      { path: 'i18n', loadChildren: './i18n/i18n.module#I18nModule' }
    ]
  },
  {
    path: '**',
    redirectTo: '/starter'
  }
];
