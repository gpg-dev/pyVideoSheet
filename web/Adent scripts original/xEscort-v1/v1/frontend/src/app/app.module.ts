import * as $ from 'jquery';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { CommonModule, LocationStrategy, HashLocationStrategy, PathLocationStrategy } from '@angular/common';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { Routes, RouterModule } from '@angular/router';

import { RestangularModule, Restangular } from 'ngx-restangular';
import { ToastyModule } from 'ng2-toasty';
import { NgSelectModule } from '@ng-select/ng-select';
import { SlickCarouselModule } from 'ngx-slick-carousel';

import { BlankComponent } from './layouts/blank/blank.component';
import { FullComponent } from './layouts/full/full.component';
import { HomeLayoutComponent } from './layouts/home/home.component';
import { AuthLayoutComponent } from './layouts/auth/auth.component';

import { SidebarComponent } from './shared/sidebar/sidebar.component';
import { BreadcrumbComponent } from './shared/breadcrumb/breadcrumb.component';
import { FooterComponent } from './shared/footer/footer.component';
import { AuthService, EscortService } from './shared/services';
import { AuthGuard } from './shared/guard/auth.guard';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { Approutes } from './app-routing.module';
import { AppComponent } from './app.component';
import { SpinnerComponent } from './shared/spinner.component';
import { HeaderComponent } from './shared/header/header.component';

import { MediaModule } from './media/media.module';
import { UtilsModule } from './utils/utils.module';

import { ConfigResolver } from './shared/resolver';

import { TranslateModule, TranslateLoader } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';

export function createTranslateLoader(http: HttpClient) {
  return new TranslateHttpLoader(http, `${window.appConfig.apiBaseUrl}/i18n/`, '.json');
};

// Function for setting the default restangular configuration
export function RestangularConfigFactory(RestangularProvider) {
  //TODO - change default config
  RestangularProvider.setBaseUrl(window.appConfig.apiBaseUrl);
  RestangularProvider.addFullRequestInterceptor((element, operation, path, url, headers, params) => {
    //Auto add token to header
    headers.Authorization = 'Bearer ' + localStorage.getItem('accessToken');
    return {
      headers: headers
    }
  });

  RestangularProvider.addErrorInterceptor((response, subject, responseHandler) => {
    //force logout and relogin
    if (response.status === 401) {
      localStorage.removeItem('accessToken');
      localStorage.removeItem('isLoggedin');
      window.location.href = '/auth/login';

      return false; // error handled
    }

    return true; // error not handled
  });
}

@NgModule({
  declarations: [
    AppComponent,
    SpinnerComponent,
    BlankComponent,
    FullComponent,
    HomeLayoutComponent,
    AuthLayoutComponent,
    BreadcrumbComponent,
    SidebarComponent,
    FooterComponent,
    HeaderComponent
  ],
  imports: [
    CommonModule,
    BrowserModule,
    BrowserAnimationsModule,
    FormsModule,
    HttpClientModule,
    NgbModule.forRoot(),
    RouterModule.forRoot(Approutes, { useHash: false }),
    // Importing RestangularModule and making default configs for restanglar
    RestangularModule.forRoot(RestangularConfigFactory),
    ToastyModule.forRoot(),
    SlickCarouselModule,
    NgSelectModule,
    MediaModule,
    UtilsModule,
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: createTranslateLoader,
        deps: [HttpClient]
      }
    }),
  ],
  providers: [{
    provide: LocationStrategy,
    useClass: PathLocationStrategy //HashLocationStrategy
  },
    AuthService,
    EscortService,
    AuthGuard,
    ConfigResolver
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
