import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Routes, RouterModule } from '@angular/router';

import { StarterComponent } from './starter.component';

import { EscortsModule } from '../escorts/escorts.module';
import { UtilsModule } from '../utils/utils.module';

const routes: Routes = [{
	path: '',
	data: {
    title: 'Home'
  },
	component: StarterComponent
}];

@NgModule({
	imports: [
  	FormsModule,
  	CommonModule,
  	RouterModule.forChild(routes),
		EscortsModule,
		UtilsModule
  ],
	declarations: [
		StarterComponent
	]
})
export class StarterModule { }
