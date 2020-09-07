import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { EscortRoutingModule } from './escort.routing';
import { EscortListingComponent } from './list/listing.component';
import { EscortService } from './escort.service';
import { EscortUpdateComponent } from './update/update.component';
import { EscortCreateComponent } from './update/create.component';
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
    EscortRoutingModule,
    NgbModule.forRoot(),
    MediaModule,
    UserModule
  ],
  declarations: [
    EscortListingComponent,
    EscortUpdateComponent,
    EscortCreateComponent
  ],
  providers: [EscortService, UserService]
})
export class EscortModule { }
