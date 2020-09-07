import { Component, OnInit } from '@angular/core';
import { EscortService } from '../escort.service';
import { Router } from '@angular/router';
import { ToastyService } from 'ng2-toasty';

@Component({
  selector: 'escort-listing',
  templateUrl: './listing.html'
})
export class EscortListingComponent implements OnInit {
  public count: Number = 0;
  public items = [];
  public currentPage: Number = 1;
  public pageSize: Number = 10;
  public searchFields: any = {
  };
  public sortOption = {
    sortBy: 'createdAt',
    sortType: 'desc'
  };

  constructor(private router: Router, private escortService: EscortService, private toasty: ToastyService) {
  }

  ngOnInit() {
    this.query();
  }

  query() {
    let params = Object.assign({
      page: this.currentPage,
      take: this.pageSize,
      sort: `${this.sortOption.sortBy}`,
      sortType:  `${this.sortOption.sortType}`
    }, this.searchFields);
    this.escortService.search(params)
      .then((resp) => {
        this.count = resp.data.count;
        this.items = resp.data.items;
      })
      .catch(() => alert('Something went wrong, please try again!'));
  }

  sortBy(field: string, type: string) {
    this.sortOption.sortBy = field;
    this.sortOption.sortType = type;
    this.query();
  }

  remove(item: any, index: number) {
    if (window.confirm('Are you sure want to delete this escort? ALL data of this escort will be lost!')) {
      this.escortService.delete(item._id)
        .then(() => {
          this.toasty.success('Escort has been deleted!');
          this.items.splice(index, 1);
        })
        .catch(() => this.toasty.error('Something went wrong, please try again!'));
    }
  }
}
