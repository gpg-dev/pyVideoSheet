import { Component, OnInit, Input } from '@angular/core';
import { ReviewService } from '../../shared/services';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
	selector: 'list-reviews',
	templateUrl: './listing.component.html'
})
export class ListReviewModalComponent implements OnInit {
	@Input() escortId: any;

	public items: any = [];
	public total: any = 0;
	public page: any = 1;
	public pageSize: any = 12;

	constructor(public activeModal: NgbActiveModal , private reviewService: ReviewService) {}

	ngOnInit() {
		this.doQuery();
	}

	doQuery() {
		this.reviewService.list({userId: this.escortId, page: this.page, take: this.pageSize})
			.then(resp => {
				this.items = resp.data.items;
				this.total = resp.data.count;
			});
	}
}
