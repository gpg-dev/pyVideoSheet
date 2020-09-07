import { Routes } from '@angular/router';
import { FullComponent } from './layouts/full/full.component';
import { HomeLayoutComponent } from './layouts/home/home.component';

import { AuthGuard } from './shared/guard/auth.guard';
import { ConfigResolver } from './shared/resolver';

export const Approutes: Routes = [
  {
    path: '',
    component: HomeLayoutComponent,
    resolve: { appConfig: ConfigResolver },
    children: [
      { path: '', loadChildren: './starter/starter.module#StarterModule' }
    ]
  },
  {
    path: 'auth',
    component: FullComponent,
    resolve: { appConfig: ConfigResolver },
    children: [
      { path: '', loadChildren: './auth/auth.module#AuthModule' }
    ]
  },
  {
    path: 'profile',
    component: FullComponent,
    resolve: { appConfig: ConfigResolver },
    canActivate: [AuthGuard],
    children: [
      { path: '', loadChildren: './profile/profile.module#ProfileModule' }
    ]
  },
  {
    path: 'escorts',
    component: FullComponent,
    resolve: { appConfig: ConfigResolver },
    children: [
      { path: '', loadChildren: './escorts/escorts.module#EscortsModule' }
    ]
  },
  {
    path: 'payment',
    component: FullComponent,
    resolve: { appConfig: ConfigResolver },
    children: [
      { path: '', loadChildren: './payment/payment.module#PaymentModule' }
    ]
  },
  {
    path: 'booking',
    component: FullComponent,
    resolve: { appConfig: ConfigResolver },
    children: [
      { path: '', loadChildren: './booking/booking.module#BookingModule' }
    ]
  },
  {
    path: '**',
    redirectTo: '/'
  }
];
