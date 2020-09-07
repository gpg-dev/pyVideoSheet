import { Component, OnInit, Input } from '@angular/core';
import { Router, ActivatedRoute, ParamMap, NavigationEnd } from '@angular/router';
import { UtilsService } from '../../shared/services';

@Component({
	templateUrl: './search.html'
})
export class SearchComponent implements OnInit {
	public filter: string = 'search';
	private navigationSubscription: any;
	public searchQuery: any = {};

	constructor(private router: Router, private route: ActivatedRoute, private utils: UtilsService) {
		this.navigationSubscription = router.events.subscribe((event: any) => {
	    if (event instanceof NavigationEnd) {
				this.searchQuery = utils.filterBooleanParams(route.snapshot.queryParams);
				this.filter = route.snapshot.params.type;
	    }
	  });
	}

	ngOnInit() { }
}
