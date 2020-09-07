import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router, ActivatedRoute, NavigationEnd } from '@angular/router';
import { Subscription } from 'rxjs/Subscription';
import { AuthService, SystemService } from '../services';

declare var $: any;

@Component({
  selector: 'app-head',
  templateUrl: './header.html'
})
export class HeaderComponent implements OnInit, OnDestroy {
  public currentUser: any;
  public isHome: boolean = false;
  public isDrop: boolean = false;
  public logo: string = '';
  public selectedFlag: string = '/assets/images/flags/gb.svg';
  private userLoadedSubscription: Subscription;
  private navigationSubscription: any;

  constructor(private router: Router, private route: ActivatedRoute, private authService: AuthService, private systemService: SystemService) {
    systemService.configs().then(appConfig => {
      if (appConfig.siteLogo) {
        this.logo = appConfig.siteLogo;
      }
    });


    this.navigationSubscription = this.router.events.subscribe((event: any) => {
      if (event instanceof NavigationEnd) {
        this.isHome = event.url === '/';

        $('html, body').animate({ scrollTop: 0 });

        if (this.isHome) {
          systemService.configs().then(appConfig => {
            if (appConfig.siteLogo) {
              this.logo = appConfig.siteLogoHome || appConfig.siteLogo;
            }
          });
        }
      }
    });
    this.userLoadedSubscription = this.authService.userLoaded$.subscribe(data => this.currentUser = data);
  }

  ngOnInit() {
    if (this.authService.isLoggedin()) {
      this.authService.getCurrentUser()
        .then(resp => this.currentUser = resp)
    }
  }

  ngOnDestroy() {
    // prevent memory leak when component destroyed
    if (this.userLoadedSubscription) {
      this.userLoadedSubscription.unsubscribe();
    }

    if (this.navigationSubscription) {
      this.navigationSubscription.unsubscribe();
    }
  }

  logout() {
    this.authService.removeToken();
    window.location.href = '/';
  }

  dropdown() {
    this.isDrop = !this.isDrop;
  }
  changeLanguage(event: string) {
    this.selectedFlag = '/assets/images/flags/' + event + '.svg';
    // change language here
  }
}
