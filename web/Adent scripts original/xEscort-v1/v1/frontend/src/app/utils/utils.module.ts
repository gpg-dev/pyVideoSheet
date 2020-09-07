import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TranslateModule } from '@ngx-translate/core';
import { MinuteToHoursPipe } from './pipes';
import { SearchbarComponent } from './components/search-bar/search-bar.component';

@NgModule({
	imports: [
		CommonModule,
		NgbModule,
		FormsModule,
		ReactiveFormsModule,
		RouterModule,
		TranslateModule.forChild()
	],
	declarations: [
		MinuteToHoursPipe,
		SearchbarComponent
	],
	exports: [
		MinuteToHoursPipe,
		SearchbarComponent,
		TranslateModule
	]
})
export class UtilsModule { }
