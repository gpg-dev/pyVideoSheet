import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router, ActivatedRoute,  NavigationEnd } from '@angular/router';
import { AuthService } from '../../shared/services';
import { ToastyService } from 'ng2-toasty';

@Component({
	templateUrl: 'signup.component.html'
})
export class SignupComponent implements OnInit, OnDestroy {
	private Auth: AuthService;
	public account: any = {
    email: '',
    password: '',
		name: '',
		phoneNumber: '',
		dialCode: '+1',
		type: 'user',
		username: ''
  };
	public confirm: any = {
		reemail: '',
		repassword: '',
		accept: false
	};
	public submitted: boolean = false;
	public dialCodes: any = [];
	public flag: string = '/assets/images/flags/us.svg';
	public reemailError: boolean = false;
	public repwError: boolean = false;
	public confirmPhone: boolean = false;
	public verificationCode: string = '';

	private navigationSubscription: any;

	constructor(auth: AuthService, public router: Router, private route: ActivatedRoute, private toasty: ToastyService) {
    this.Auth = auth;
		this.navigationSubscription = this.router.events.subscribe((event: any) => {
	    if (event instanceof NavigationEnd) {
				this.account.type = this.route.snapshot.paramMap.get('type');
	    }
	  });
		if (this.Auth.isLoggedin()) {
			this.Auth.removeToken();
		}
  }

	ngOnInit() {
		this.dialCodes = this.Auth.getDialCodes();
	}

	ngOnDestroy() {
    // avoid memory leaks here by cleaning up after ourselves. If we
    // don't then we will continue to run our initialiseInvites()
    // method on every navigationEnd event.
    if (this.navigationSubscription) {
       this.navigationSubscription.unsubscribe();
    }
  }

	public onlyNumberKey(event) {
    return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57;
  }

	public selectDial(dial: any) {
		this.account.dialCode = dial.dialCode;
		this.flag = `/assets/images/flags/${dial.code.toLowerCase()}.svg`;
	}

	public submit(frm: any) {
		this.submitted = true;
		if (frm.invalid || this.account.password !== this.confirm.repassword || this.account.email !== this.confirm.reemail) {
			return;
		}

		let data = Object.assign({}, this.account);
		if (this.account.type === 'escort') {
			data.phoneNumber = `${this.account.dialCode}${this.account.phoneNumber}`;
		}
		this.Auth.register(data)
			.then(resp => {
				if (this.account.type !== 'escort') {
						this.toasty.success('Your account has been created, please verify your email then login');
						this.router.navigate(['/auth/login']);

				}else {
					this.toasty.success('Your account has been created, please verify your email & phone number then login');
					this.confirmPhone = true;
				}

			})
			.catch(err => this.toasty.error(err.data.data.message));
	}

	public verifyPhone(frm: any) {
		if (frm.invalid) {
			return;
		}
		this.Auth.verifyPhone({
			code: this.verificationCode,
			phoneNumber: `${this.account.dialCode}${this.account.phoneNumber}`
		})
			.then(resp => {
				this.toasty.success('Your phone number has been verified');
				return this.router.navigate(['/auth/login']);
			})
			.catch(err => this.toasty.error(err.data.data.message));;
	}
}
