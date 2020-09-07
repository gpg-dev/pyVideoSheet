import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { UserCreateComponent } from './create/create.component';
import { UserListingComponent } from './list/listing.component';
import { UserUpdateComponent } from './update/update.component';

const routes: Routes = [
  { path: 'create', component: UserCreateComponent },
  { 
    path: 'list', 
    component: UserListingComponent,
    data: {
      title: 'Users manager',
      urls: [{ title: 'Users',url: '/users/list' }, { title: 'Listing' }]
    }
  },
  { 
    path: 'update/:id', component: UserUpdateComponent,
    data: {
      title: 'Users manager',
      urls: [{ title: 'Users',url: '/users/list' }, { title: 'Update' }]
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UserRoutingModule { }
