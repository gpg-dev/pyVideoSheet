import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { EscortListingComponent } from './list/listing.component';
import { EscortUpdateComponent } from './update/update.component';
import { EscortCreateComponent } from './update/create.component';

const routes: Routes = [
  {
    path: 'list',
    component: EscortListingComponent,
    data: {
      title: 'Escorts manager',
      urls: [{ title: 'Escorts',url: '/escorts/list' }, { title: 'Listing' }]
    }
  },
  {
    path: 'update/:id', component: EscortUpdateComponent,
    data: {
      title: 'Escorts manager',
      urls: [{ title: 'Escorts',url: '/escorts/list' }, { title: 'Update' }]
    }
  },
  {
    path: 'create', component: EscortCreateComponent,
    data: {
      title: 'Escorts manager',
      urls: [{ title: 'Escorts',url: '/escorts/list' }, { title: 'Create' }]
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EscortRoutingModule { }
