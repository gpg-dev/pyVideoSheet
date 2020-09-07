import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { JsonpModule } from '@angular/http';

import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { LoginComponent } from './login/login.component';
import { SignupComponent } from './signup/signup.component';
import { ForgotComponent } from './forgot/forgot.component';
import { UtilsModule } from '../utils/utils.module';


const routes: Routes = [{
  path: 'login',
  component: LoginComponent
}, {
  path: 'signup/:type',
  component: SignupComponent
}, {
  path: 'forgot',
  component: ForgotComponent
}];

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forChild(routes),
    FormsModule,
    ReactiveFormsModule,
    JsonpModule,
    NgbModule,
    UtilsModule
  ],
  declarations: [
    LoginComponent,
    SignupComponent,
    ForgotComponent
  ]
})

export class AuthModule { }
