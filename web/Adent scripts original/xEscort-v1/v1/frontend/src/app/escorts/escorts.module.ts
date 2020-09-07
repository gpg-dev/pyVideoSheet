import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Routes, RouterModule } from '@angular/router';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { SlickCarouselModule } from 'ngx-slick-carousel';
import { DetailsComponent } from './details/details.component';
import { ListEscortsComponent } from './list/list.component';
import { ListReviewModalComponent } from './reviews/listing.component';
import { SendMessageComponent, SendMessageModalComponent } from './send-message/send-message.component';
import { SearchComponent } from './search/search.component';
import { UtilsModule } from '../utils/utils.module';
import { RatingButtonComponent } from '../shared/rate-review/rating-button.component';
import { RatingModalComponent } from '../shared/rate-review/rating-modal.component';

const routes: Routes = [{
	path: 'filter/:type',
	component: SearchComponent
}, {
	path: ':alias',
	component: DetailsComponent
}];

@NgModule({
	imports: [
  	FormsModule,
  	CommonModule,
  	RouterModule.forChild(routes),
		NgbModule.forRoot(),
		SlickCarouselModule,
		UtilsModule
  ],
	declarations: [
		DetailsComponent,
		ListEscortsComponent,
		SendMessageComponent,
		SendMessageModalComponent,
		SearchComponent,
		RatingButtonComponent,
		RatingModalComponent,
		ListReviewModalComponent
	],
	exports: [
		ListEscortsComponent
	],
  entryComponents: [
    SendMessageModalComponent,
		RatingModalComponent,
		ListReviewModalComponent
  ]
})
export class EscortsModule { }
