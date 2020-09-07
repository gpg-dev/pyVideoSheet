import { Component, OnInit, Input } from '@angular/core';
import { BookingService } from '../../shared/services';

@Component({
	selector: 'list-booking',
	templateUrl: './listing.component.html'
})
export class ListBookingComponent implements OnInit {
	public items: any = [];
	public total: any = 0;
	public page: any = 1;
	public pageSize: any = 12;

	constructor( private bookingService: BookingService) {}

	ngOnInit() {
		this.doQuery();
	}

	doQuery() {
		this.bookingService.list({page: this.page, take: this.pageSize})
			.then(resp => {
				this.items = resp.data.items;
				this.total = resp.data.count;
			});
	}
}
