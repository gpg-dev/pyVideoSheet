import { Component, OnInit, Input, Output } from '@angular/core';
import { AuthService } from '../../shared/services';
import { ToastyService } from 'ng2-toasty';
import * as _ from 'lodash';

@Component({
	selector: 'user-profile-update',
	templateUrl: './form.html'
})
export class UserProfileUpdateComponent implements OnInit {
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
		onFinish: (resp) => this.media.unshift(resp.data)
	};

	constructor(private authService: AuthService, private toasty: ToastyService) { }

	ngOnInit() {
		this.profileEdit = Object.assign({}, this.profile);
	}

	updateData(name: string, fields: any = []) {
		let field: any = _.pick(this.profileEdit, fields);
		this.authService.update(field).then(resp => {
			_.merge(this.profile, field);
			this.editFields[name] = false;
		});
	}

	updatePassword() {
		this.authService.update({
			password: this.newPassword
		}).then(resp => {
			this.newPassword = '';
			this.editFields.password = false;
			this.toasty.success('Updated password successfully');
		})
		.catch((err) => { return this.toasty.error(err.data.message) });
	}
}
