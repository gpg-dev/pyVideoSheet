import { enableProdMode } from '@angular/core';
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

import { AppModule } from './app/app.module';
import { environment } from './environments/environment';

if (environment.production) {
  enableProdMode();
}

declare global {
  interface Window { appConfig: any; }
}

//export version and build number to global
window.appConfig = {
  version: environment.version,
  build: environment.build,
  apiBaseUrl: environment.apiBaseUrl,
  appBaseUrl: environment.appBaseUrl,
  _loadedFavicon: false,
  _loadedCodeHead: false,
  _loadedCodeBody: false
};

platformBrowserDynamic().bootstrapModule(AppModule)
  .catch(err => console.log(err));
