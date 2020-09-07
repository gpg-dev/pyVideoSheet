import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { InvoiceListingComponent } from './list/listing.component';

const routes: Routes = [
  {
    path: 'list',
    component: InvoiceListingComponent,
    data: {
      title: 'Invoices manager',
      urls: [{ title: 'Invoices',url: '/invoices/list' }, { title: 'Listing' }]
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class InvoiceRoutingModule { }
