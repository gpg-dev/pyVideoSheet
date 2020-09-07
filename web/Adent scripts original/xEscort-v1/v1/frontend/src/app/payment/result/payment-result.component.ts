import { Component, OnInit, OnDestroy, Input } from '@angular/core';
import { Router, ActivatedRoute, ParamMap, NavigationEnd } from '@angular/router';
import { ToastyService } from 'ng2-toasty';

@Component({
	templateUrl: './payment-result.html'
})
export class PaymentResultComponent implements OnInit {
  public result: string = 'success';

	constructor(private route: ActivatedRoute) {
    this.result = route.snapshot.params.type;
  }

	ngOnInit() { }
}
