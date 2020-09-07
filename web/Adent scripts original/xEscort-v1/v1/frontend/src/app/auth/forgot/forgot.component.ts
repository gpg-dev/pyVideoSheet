import { Component} from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../shared/services';
import { ToastyService } from 'ng2-toasty';

@Component({
	templateUrl: 'forgot.component.html'
})
export class ForgotComponent {
	Auth: AuthService;
	credentials = {
    email: ''
  };
	submitted: any = false;

	constructor(auth: AuthService, public router: Router, private toasty: ToastyService) {
    this.Auth = auth;
  }

	submit() {
		this.submitted = true;
		this.Auth.forgot(this.credentials.email).then(() => {
			// TODO - separate escort and user route?
			this.toasty.success('Your request has been sent, please check your email inbox.')
			this.router.navigate(['/auth/login']);
		})
    .catch((err) => { return this.toasty.error(err.data.data ? err.data.data.message : 'Something went wrong, please try again!')});
	}
}
