import { Component, OnInit, Input, EventEmitter, Output } from '@angular/core';
import { Router, ActivatedRoute, ParamMap, NavigationEnd } from '@angular/router';
import { EscortService, UtilsService } from '../../shared/services';

@Component({
	selector: 'list-escorts',
	templateUrl: './list.html'
})
export class ListEscortsComponent implements OnInit {
	@Input() filter: any;
	@Input() title: string;
	@Input() searchQuery: any = {
    country: ''
  };
	@Output() onQueryDone = new EventEmitter();

	public items: any = [];
	public total: any = 0;
	public page: any = 1;
	public take: any = 12;
	private navigationSubscription: any;
	private sort: string = '';
	private sortType: string = '';

	constructor(private escortService: EscortService, private router: Router, private route: ActivatedRoute,
		private utils: UtilsService) {
		this.navigationSubscription = router.events.subscribe((event: any) => {
	    if (this.filter === 'search' && event instanceof NavigationEnd) {
				this.searchQuery = utils.filterBooleanParams(route.snapshot.queryParams);
				this.doQuery();
	    }
	  });
	}

	ngOnInit() {
		this.doQuery();
	}

	changeSort(sort: string, $event: any) {
		this.sort = sort;
		this.sortType = $event.target.value;
		this.page = 1;
		this.doQuery();
	}

	doQuery() {
		// TODO - do query with type here
		let query: any = {
			page: this.page,
			take: this.take
		};
		if (this.filter === 'latest') {
			query.sort = this.sort;
			query.sortType = this.sortType;
		}
		if (this.filter === 'featured') {
			query.featured = true;
			query.sort = this.sort;
			query.sortType = this.sortType;
		}
		if (this.filter === 'search') {
			query = Object.assign(this.searchQuery, {
				page: 1,
				sort: this.sort,
				sortType: this.sortType
			});
		}

		this.escortService.search(query)
			.then(resp => {
				this.items = resp.data.items;
				this.total = resp.data.count;

				this.onQueryDone.emit(resp.data), () => (null)
			});
	}
}
