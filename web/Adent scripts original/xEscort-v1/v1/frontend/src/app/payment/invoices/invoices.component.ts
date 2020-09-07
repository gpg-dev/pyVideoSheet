import { Component, OnInit, OnDestroy, Input } from '@angular/core';
import { Router, ActivatedRoute, ParamMap, NavigationEnd } from '@angular/router';
import { ToastyService } from 'ng2-toasty';
import { TransactionService } from '../../shared/services';

@Component({
  selector: 'invoices',
	templateUrl: './invoices.html'
})
export class InvoicesComponent implements OnInit {
  public items: any = [];
  public total: any = 0;
  public page: number = 1;

	constructor(private route: ActivatedRoute, private transactionService: TransactionService) {
  }

	ngOnInit() {
    this.query();
  }

  query() {
    this.transactionService.invoices({
      page: this.page
    })
      .then(resp => {
        this.total = resp.data.count;
        this.items = resp.data.items;
      });
  }
}
