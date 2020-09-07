import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { BookingRoutingModule } from './booking.routing';
import { BookingListingComponent } from './list/listing.component';
import { BookingService } from './booking.service';
import { MediaModule } from '../media/media.module';
import { UserModule } from '../user/user.module';
import { UserService } from '../user/user.service';
import { NgSelectModule } from '@ng-select/ng-select';
import { TimePipe } from '../shared/pipes/time.pipe';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    NgSelectModule,
    //our custom module
    BookingRoutingModule,
    NgbModule.forRoot(),
    MediaModule,
    UserModule
  ],
  declarations: [
    BookingListingComponent,
    TimePipe
  ],
  providers: [BookingService, UserService]
})
export class BookingModule { }
