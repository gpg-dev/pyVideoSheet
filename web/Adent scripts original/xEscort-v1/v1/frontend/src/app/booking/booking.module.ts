import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { Routes, RouterModule } from '@angular/router';
import { UtilsModule } from '../utils/utils.module';
import { PaymentModule } from '../payment/payment.module';

import { BookEscortComponent } from './book-escort/book-escort.component';
import { ListBookingComponent } from './list-booking/listing.component';
import { CheckoutModalComponent } from '../payment/checkout-modal/checkout-modal.component';

import { TimePipe } from '../shared/pipes/time.pipe';

import { EscortService, BookingService } from '../shared/services';

const routes: Routes = [{
  path: 'escorts/:username',
  component: BookEscortComponent
},
{
  path: 'listing/:username',
  component: ListBookingComponent
}];

@NgModule({
  imports: [
    FormsModule,
    CommonModule,
    RouterModule.forChild(routes),
    NgbModule.forRoot(),
    UtilsModule,
    PaymentModule
  ],
  declarations: [
    BookEscortComponent,
    ListBookingComponent,
    TimePipe
  ],
  exports: [],
  providers: [
    EscortService,
    BookingService
  ],
  entryComponents: [
    CheckoutModalComponent
  ]
})
export class BookingModule { }
