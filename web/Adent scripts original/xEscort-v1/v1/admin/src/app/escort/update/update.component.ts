import { Component, OnInit } from '@angular/core';
import { EscortService } from '../escort.service';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { ToastyService } from 'ng2-toasty';
import * as _ from 'lodash';

@Component({
  selector: 'escort-update',
  templateUrl: './form.html'
})
export class EscortUpdateComponent implements OnInit {
  public isNew: boolean = false;
  public info: any = {};
  public avatarUrl = '';
  public isSubmitted = false;
  public avatarOptions: any = {};
  public mediaOptions: any = {};
  public escort: any = {};
  public media: any = [];
  public languages: any = [];
  public selectedLangauges: any = [];
  public minutes: any = [];
  public tab = 'profile';
  private escortId: string;

  constructor(private router: Router, private escortService: EscortService, private toasty: ToastyService, private route: ActivatedRoute) { }

  ngOnInit() {
    this.escortId = this.route.snapshot.paramMap.get('id');
    this.avatarOptions = {
      url: window.appConfig.apiBaseUrl + '/users/' + this.escortId + '/avatar',
      fileFieldName: 'avatar',
      onFinish: resp => this.avatarUrl = resp.data.url
    };

    this.mediaOptions = {
      customFields: {
        systemType: 'profile-media',
        ownerId: this.escortId
      },
      multiple: true,
      autoUpload: true,
      onCompleteItem: resp => this.media.push(resp.data)
    };

    this.minutes = this.escortService.getMinutes();

    this.escortService.findOne(this.escortId).then(resp => {
      this.escort = resp.data.escort;
      this.media = resp.data.media;
      this.info = _.clone(resp.data.escort);
      this.avatarUrl = resp.data.escort.avatarUrl;
      this.populateLanguage();
    });

    this.escortService.languages().then(resp => {
      this.languages = resp.data;
      this.populateLanguage();
    });
  }

  populateLanguage() {
    if (_.isEmpty(this.info) || _.isEmpty(this.languages)) {
      return;
    }

    this.selectedLangauges = this.languages.filter(lang => this.info.languages.indexOf(lang.id) > -1);
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
    this.escortService.update(this.escortId, this.info).then(resp => {
      this.toasty.success('Updated successfuly!');
      this.router.navigate(['/escorts/list']);
    }).catch((err) => this.toasty.error(err.data.data.message || err.data.data.email));
  }
}
