import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, NavigationEnd } from '@angular/router';

declare var $: any;

@Component({
  selector: 'auth-layout',
  templateUrl: './auth.component.html',
  styleUrls: ['./auth.component.scss']
})
export class AuthLayoutComponent implements OnInit {

  constructor(public router: Router, private route: ActivatedRoute) { }

  ngOnInit() {
    const appConfig = this.route.snapshot.data.appConfig;
    if (appConfig && appConfig.siteFavicon && !window.appConfig._loadedFavicon) {
      $('#favicon').attr('href', appConfig.siteFavicon);
      window.appConfig._loadedFavicon = true;
    }
    if (appConfig && appConfig.codeHead && !window.appConfig._loadedCodeHead) {
      $('head').append(appConfig.codeHead);
      window.appConfig._loadedCodeHead = true;
    }
    if (appConfig && appConfig.codeBody && !window.appConfig._loadedCodeBody) {
      $('body').prepend(appConfig.codeBody);
      window.appConfig._loadedCodeBody = true;
    }
  }
}
