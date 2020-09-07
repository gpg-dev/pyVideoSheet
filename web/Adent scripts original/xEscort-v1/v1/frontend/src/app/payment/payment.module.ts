import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Routes, RouterModule } from '@angular/router';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { PaymentResultComponent } from './result/payment-result.component';
import { SubscribeComponent } from './subscribe/subscribe.component';
import { InvoicesComponent } from './invoices/invoices.component';
import { CheckoutModalComponent } from './checkout-modal/checkout-modal.component';
import { UtilsModule } from '../utils/utils.module';

const routes: Routes = [{
  path: 'result/:type',
  component: PaymentResultComponent
}, {
  path: 'subscribe',
  component: SubscribeComponent
}];

@NgModule({
  imports: [
    FormsModule,
    CommonModule,
    RouterModule.forChild(routes),
    NgbModule.forRoot(),
    UtilsModule
  ],
  declarations: [
    PaymentResultComponent,
    SubscribeComponent,
    InvoicesComponent,
    CheckoutModalComponent
  ],
  exports: [
    CheckoutModalComponent
  ],
  entryComponents: [
    CheckoutModalComponent
  ]
})
export class PaymentModule { }
