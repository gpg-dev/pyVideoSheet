import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { InvoiceRoutingModule } from './invoice.routing';
import { InvoiceListingComponent } from './list/listing.component';
import { InvoiceService } from './invoice.service';
import { MediaModule } from '../media/media.module';
import { UserModule } from '../user/user.module';
import { UserService } from '../user/user.service';
import { NgSelectModule } from '@ng-select/ng-select';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    NgSelectModule,
    //our custom module
    InvoiceRoutingModule,
    NgbModule.forRoot(),
    MediaModule,
    UserModule
  ],
  declarations: [
    InvoiceListingComponent
  ],
  providers: [InvoiceService, UserService]
})
export class InvoiceModule { }
