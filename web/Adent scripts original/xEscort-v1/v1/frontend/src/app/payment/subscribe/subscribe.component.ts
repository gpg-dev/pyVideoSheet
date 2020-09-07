import { Component, OnInit, OnDestroy, Input } from '@angular/core';
import { Router, ActivatedRoute, ParamMap, NavigationEnd } from '@angular/router';
import { ToastyService } from 'ng2-toasty';
import { TransactionService, AuthService, SystemService, UtilsService } from '../../shared/services';

@Component({
	templateUrl: './subscribe.html'
})
export class SubscribeComponent implements OnInit {
	public currentUser: any;
	public appConfig: any;
	public loading: boolean = false;

	constructor(private route: ActivatedRoute, private authService: AuthService,
		private systemService: SystemService,
		private transactionService: TransactionService,
		private utilService: UtilsService) {
	}

	ngOnInit() {
		this.authService.getCurrentUser()
			.then(resp => this.currentUser = resp);
		this.systemService.configs().then(config => this.appConfig = config);
	}

	subscribeFeatured(gateway: string = 'paypal') {
		this.utilService.setLoading(true);
		this.transactionService.request({
			service: this.currentUser.type === 'escort' ? 'escort_subscription' : 'user_subscription',
			gateway: gateway,
			redirectSuccessUrl: `${window.appConfig.appBaseUrl}/payment/result/success`,
			redirectCancelUrl: `${window.appConfig.appBaseUrl}/payment/result/cancelled`
		})
			.then(resp => {
				window.location.href = resp.data.redirectUrl;
			})
			.catch(() => this.utilService.setLoading(false));
	}
}
