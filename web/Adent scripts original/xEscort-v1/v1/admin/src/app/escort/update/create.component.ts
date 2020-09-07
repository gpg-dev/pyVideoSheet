import { Component, OnInit } from '@angular/core';
import { EscortService } from '../escort.service';
import { Router } from '@angular/router';
import { ToastyService } from 'ng2-toasty';
import * as _ from 'lodash';

@Component({
  selector: 'escort-create',
  templateUrl: './form.html'
})
export class EscortCreateComponent implements OnInit {
  public isNew: boolean = true;
  public info: any = {
    gender: 'male',
    serviceFor: 'female'
  };
  public isSubmitted = false;
  public escort: any = {};
  public media: any = [];
  public languages: any = [];
  public selectedLangauges: any = [];
  public tab = 'profile';

  constructor(private router: Router, private escortService: EscortService, private toasty: ToastyService) { }

  ngOnInit() {
    this.escortService.languages().then(resp => {
      this.languages = resp.data;
    });
  }

  changeTab(tab) {
    this.tab = tab;
  }

  save(frm: any) {
    this.isSubmitted = true;
    if (!frm.valid) {
      return this.toasty.error('Something went wrong, please check and try again!');
    }

    this.info.languages = this.selectedLangauges.map(l => l.id);
    this.escortService.create(this.info).then(resp => {
      this.toasty.success('Created successfuly!');
      this.router.navigate(['/escorts/update/' + resp.data._id]);
    }).catch((err) => {
      let message = 'Something went wrong. Ensure email and username is unique value, please check!';
      // if (err.data.data && err.data.data.message) {
      //   message = err.data.data.message;
      // } else if (err.data.message) {
      //   message = err.data.message;
      // }
      this.toasty.error(message);
    });
  }
}
