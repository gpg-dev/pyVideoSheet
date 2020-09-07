import { Component, OnInit } from '@angular/core';
import { InvoiceService } from '../invoice.service';
import { Router } from '@angular/router';
import { ToastyService } from 'ng2-toasty';

@Component({
  selector: 'invoice-listing',
  templateUrl: './listing.html'
})
export class InvoiceListingComponent implements OnInit {
  public total: Number = 0;
  public items = [];
  public currentPage: Number = 1;
  public pageSize: Number = 10;
  public searchFields: any = {
    type: ''
  };
  public sortOption = {
    sortBy: 'createdAt',
    sortType: 'desc'
  };

  constructor(private router: Router, private invoiceService: InvoiceService, private toasty: ToastyService) {
  }

  ngOnInit() {
    this.query();
  }

  query() {
    let params = Object.assign({
      page: this.currentPage,
      take: this.pageSize,
      sort: `${this.sortOption.sortBy}:${this.sortOption.sortType}`
    }, this.searchFields);
    this.invoiceService.search(params)
      .then((resp) => {
        this.total = resp.data.count;
        this.items = resp.data.items;
      })
      .catch(() => alert('Something went wrong, please try again!'));
  }

  sortBy(field: string, type: string) {
    this.sortOption.sortBy = field;
    this.sortOption.sortType = type;
    this.query();
  }
}
