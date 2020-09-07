import { Component} from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../shared/services';
import { ToastyService } from 'ng2-toasty';

@Component({
	templateUrl: 'login.component.html'
})
export class LoginComponent {
	Auth: AuthService;
	credentials = {
    email: '',
    password: ''
  };

	constructor(auth: AuthService, public router: Router, private toasty: ToastyService) {
    this.Auth = auth;
		if (this.Auth.isLoggedin()) {
			this.Auth.removeToken();
		}
  }

	login() {
		this.Auth.login(this.credentials).then(() => {
			// TODO - separate escort and user route?
			this.router.navigate(['/profile/update']);
		})
    .catch((err) => alert(err.data.data ? err.data.data.message : 'Something went wrong, please try again!'));
	}
}
