import { Component, OnInit, OnDestroy, Input } from '@angular/core';
import { ReviewService , AuthService, EscortService } from '../../shared/services';
import { Router, ActivatedRoute, ParamMap, NavigationEnd } from '@angular/router';
import { NgbModal, NgbModalOptions } from '@ng-bootstrap/ng-bootstrap';
import { ListReviewModalComponent } from '../reviews/listing.component';

@Component({
	templateUrl: './details.html'
})
export class DetailsComponent implements OnInit, OnDestroy {
	private navigationSubscription: any;
	public escort: any;
	public media: any = [];
	public languages = [];
	public slideConfig: any = {
		slidesToShow: 3
	};
	public showImage: any = '';
	public reviews: any = [];
	public modalRef: any;
	public others: any = [];

	constructor(private router: Router, private route: ActivatedRoute,  private modalService: NgbModal,
		private escortService: EscortService, private reviewService: ReviewService, private authService: AuthService) {
		this.navigationSubscription = router.events.subscribe((event: any) => {
		    if (event instanceof NavigationEnd) {
					this.doQuery();
					this.queryOthers();
		    }
		  });
	}

	ngOnInit() {  }

	ngOnDestroy() {
		// avoid memory leaks here by cleaning up after ourselves. If we
		// don't then we will continue to run our initialiseInvites()
		// method on every navigationEnd event.
		if (this.navigationSubscription) {
			 this.navigationSubscription.unsubscribe();
		}
	}

	doQuery() {
		let alias = this.route.snapshot.paramMap.get('alias');
		this.escortService.find(alias)
			.then(resp => {
				this.escort = resp.data.escort;
				this.media = resp.data.media;
				const item = { mediumUrl: resp.data.escort.avatarUrl, thumbUrl: resp.data.escort.avatarUrl};
				this.media.unshift(item);
				this.showImage = resp.data.escort.avatarUrl;
				this.reviewService.list({userId: resp.data.escort._id, take: 4}).then((resp) => {
					this.reviews = resp.data.items;
				});
				this.escortService.languages()
					.then(resp => {
						// map user langauges
						resp.data.forEach(lang => {
							if (this.escort.languages.indexOf(lang.id) > -1) {
								this.languages.push(lang);
							}
						});
					});
			});
		// TODO - find other content
	}

	queryOthers() {
		this.escortService.search({take: 8, page: 1}).then(resp => this.others = resp.data.items);
	}

	viewAllReview(escortId: any) {
		let ngbModalOptions: NgbModalOptions = {
			backdrop : 'static',
			keyboard : false,
			size: 'lg'
		};
		this.modalRef = this.modalService.open(ListReviewModalComponent, ngbModalOptions);
		this.modalRef.componentInstance.escortId = escortId;
	}

	onRating(event:any) {
		this.authService.getCurrentUser().then(resp => event.rater = resp);
		this.reviews.unshift(event);
	}
}
