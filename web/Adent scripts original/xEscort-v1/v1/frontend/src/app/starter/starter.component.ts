import { Component, OnDestroy, OnInit } from '@angular/core';
import { SeoService } from '../shared/services';
import { ActivatedRoute } from '@angular/router';

declare var $: any;

@Component({
	templateUrl: './starter.component.html'
})
export class StarterComponent implements OnInit, OnDestroy {
	public hideFeatured: boolean = true;
	public hideLatest: boolean = true;

	constructor(seoService: SeoService, private route: ActivatedRoute) {
		const dataSeo = route.snapshot.data.appConfig;
		if (dataSeo) {
			seoService.update(dataSeo.siteName, dataSeo.homeSEO);
		}
	}

	ngOnInit() {
		$(document.body).addClass('home-page');
	}

	ngOnDestroy() {
		$(document.body).removeClass('home-page');
	}

	queryDone(type: string, data: any) {
		if (type === 'featured') {
			this.hideFeatured = !data.count;
		}

		if (type === 'latest') {
			this.hideLatest = !data.count;
		}
	}
}
