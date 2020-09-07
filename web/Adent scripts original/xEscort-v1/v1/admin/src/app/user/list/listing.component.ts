import { Component, OnInit } from '@angular/core';
import { UserService } from '../user.service';
import { Router } from '@angular/router';
import { ToastyService } from 'ng2-toasty';

@Component({
  selector: 'user-listing',
  templateUrl: './listing.html'
})
export class UserListingComponent implements OnInit {
  public count: Number = 0;
  public items = [];
  public currentPage: Number = 1;
  public pageSize: Number = 10;
  public searchFields: any = {
    type: 'user'
  };
  public sortOption = {
    sortBy: 'createdAt',
    sortType: 'desc'
  };

  constructor(private router: Router, private userService: UserService, private toasty: ToastyService) {
  }

  ngOnInit() {
    this.query();
  }

  query() {
    let params = Object.assign({
      page: this.currentPage,
      take: this.pageSize,
      sort: `${this.sortOption.sortBy}`,
      sortType: `${this.sortOption.sortType}`
    }, this.searchFields);
    this.userService.search(params)
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
    if (window.confirm('Are you sure want to delete this user? ALL data of this user will be lost')) {
      this.userService.delete(item._id)
        .then(() => {
          this.toasty.success('User has been deleted!');
          this.items.splice(index, 1);
        })
        .catch(() => this.toasty.error('Something went wrong, please try again!'));
    }
  }
}
