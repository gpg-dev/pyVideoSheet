import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Routes, RouterModule } from '@angular/router';
import { NgSelectModule } from '@ng-select/ng-select';

import { EscortProfileUpdateComponent } from './escort-update/escort-update.component';
import { UserProfileUpdateComponent } from './user-update/user-update.component';
import { ProfileUpdateComponent } from './profile-update/profile-update.component';
import { PhoneVerifyModal } from './phone-verify/phone-verify-modal.component';

import { MediaModule } from '../media/media.module';
import { UtilsModule } from '../utils/utils.module';

const routes: Routes = [{
	path: 'update',
	component: ProfileUpdateComponent
}];

@NgModule({
	imports: [
  	FormsModule,
  	CommonModule,
  	RouterModule.forChild(routes),
		NgSelectModule,
		MediaModule,
		UtilsModule
  ],
	declarations: [
		EscortProfileUpdateComponent,
		ProfileUpdateComponent,
		UserProfileUpdateComponent,
		PhoneVerifyModal
	],
	entryComponents: [
		PhoneVerifyModal
	]
})
export class ProfileModule { }
