import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { BookingListingComponent } from './list/listing.component';

const routes: Routes = [
  {
    path: 'list',
    component: BookingListingComponent,
    data: {
      title: 'Bookings manager',
      urls: [{ title: 'Bookings',url: '/bookings/list' }, { title: 'Listing' }]
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class BookingRoutingModule { }
