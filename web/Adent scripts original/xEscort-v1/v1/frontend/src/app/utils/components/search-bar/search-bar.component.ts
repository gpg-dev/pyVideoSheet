import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ParamMap, NavigationEnd } from '@angular/router';
import { EscortService, AuthService, UtilsService } from '../../../shared/services';
import * as _ from 'lodash';

@Component({
  selector: 'search-bar',
  templateUrl: './search-bar.html'
})
export class SearchbarComponent implements OnInit {
  public filter: any = {
    country: ''
  };
  public countries = [];
  private navigationSubscription: any;

  constructor(private escortService: EscortService, private Auth: AuthService,
    private router: Router, private route: ActivatedRoute, private utils: UtilsService) {
    this.navigationSubscription = router.events.subscribe((event: any) => {
	    if (event instanceof NavigationEnd) {
				Object.assign(this.filter, utils.filterBooleanParams(route.snapshot.queryParams));
	    }
	  });
  }

  ngOnInit() {
    this.countries = _.sortBy(this.Auth.getDialCodes(), c => c.name);
  }

  search() {
    let field = this.filter;
    if( field.country == '' && !field.city && !field.username &&
        !field.realPics && !field.verifiedContact && !field.withVideo &&
        !field.pornstar && !field.online && !field.availableTravel && !field.naturalPhoto) {
          return
    }
    this.router.navigate(['/escorts/filter/search'], {
      queryParams: this.utils.filterBooleanParams(field)
    });
  }
}
