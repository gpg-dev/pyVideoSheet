import { Component, OnInit, Input, Output } from '@angular/core';
import { NgbModal, NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { AuthService, EscortService } from '../../shared/services';
import * as _ from 'lodash';
import { PhoneVerifyModal } from '../phone-verify/phone-verify-modal.component';
import { ToastyService } from 'ng2-toasty';

@Component({
	selector: 'escort-profile-update',
	templateUrl: './form.html'
})
export class EscortProfileUpdateComponent implements OnInit {
	@Input() profile: any;
	public profileEdit: any;
	public languages: any = [];
	public selectedLangauges: any = [];
	public minutes: any = [];
	public media: any = [];
	public newRate: any = {
		price: '',
		duration: ''
	};

	public editFields: any = {
		name: false,
		bio: false,
		about: false,
		contact: false,
		available: false,
		travel: false,
		rates: false,
		password: false
	};
	public newPassword: string = '';

	public avatarOption: any = {
		url: window.appConfig.apiBaseUrl + '/users/avatar',
		fileFieldName: 'avatar',
		onFinish: (resp) => {
			this.profile.avatarUrl = resp.data.url;
		},
		hintText: 'Change your avatar'
	};

	public imageOption: any = {
		onFinish: (resp) => resp.forEach(item => this.media.unshift(item.data)),
		hintText: 'Use images for slider',
		multiple: true
	};

	constructor(private authService: AuthService, private escortService: EscortService,
		 private modalService: NgbModal, private toasty: ToastyService) { }

	ngOnInit() {
			this.profileEdit = _.merge({
				rates: [{
					price: 10,
					duration: '30m',
					text: '30 minutes'
				}, {
					price: 20,
					duration: '1h',
					text: '1 hour'
				}, {
					price: 30,
					duration: '2h',
					text: '2 hours'
				}, {
					price: 100,
					duration: 'overnight',
					text: 'Overnight'
				}]
			},this.profile);

		this.minutes = this.escortService.getMinutes();
    this.escortService.languages().then(resp => {
      this.languages = resp.data;
      this.populateLanguage();
    });

		this.escortService.media().then(resp => {
      this.media = resp.data;
    });
	}

	populateLanguage() {
    if (_.isEmpty(this.profile) || _.isEmpty(this.languages)) {
      return;
    }

    this.selectedLangauges = this.languages.filter(lang => this.profile.languages.indexOf(lang.id) > -1);
  }

	updateData(name: string, fields: any = []) {
		let field: any = _.pick(this.profileEdit, fields);

		if ( name === 'rates' && field.rates[0].price < 0 || name === 'rates' && field.rates[1].price < 0 ||
				 name === 'rates' && field.rates[2].price < 0 || name === 'rates' && field.rates[3].price < 0 ) {
			return this.toasty.error('Price must be integer number')
		}

		if (fields.indexOf('languages') > -1) {
			field.languages = this.selectedLangauges.map(l => l.id);
		}

		this.escortService.update(field).then(resp => {
			_.merge(this.profile, field);
			this.editFields[name] = false;
		});
	}

	updatePassword() {
		this.escortService.update({
			password: this.newPassword
		}).then(resp => {
			this.newPassword = '';
			this.editFields.password = false;
		});
	}

	removeMedia(media: any, index: any) {
		this.escortService.removeMedia(media._id)
			.then(() => this.media.splice(index, 1))
	}

	addRate() {
		this.profileEdit.rates.push(this.newRate);
		this.newRate = {
			price: '',
			duration: ''
		};
	}

	removeRate(index: any) {
		this.profileEdit.rates.splice(index, 1);
	}

	changePhone() {
		this.modalService.open(PhoneVerifyModal)
			.result.then((result) => {
				this.profile.phoneNumber = result;
	    }, (reason) => {
	      //this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
	    });
	}
}
