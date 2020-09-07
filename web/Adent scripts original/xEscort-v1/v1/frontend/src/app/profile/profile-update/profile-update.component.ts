import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../shared/services';

@Component({
	templateUrl: './profile-update.html'
})
export class ProfileUpdateComponent implements OnInit {
  public profile: any;

	constructor(private authService: AuthService) { }

	ngOnInit() {
    this.authService.getCurrentUser()
      .then(resp => this.profile = resp);
  }
}
