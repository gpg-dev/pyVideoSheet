import { Injectable } from '@angular/core';
import { RestangularModule, Restangular } from 'ngx-restangular';
import 'rxjs/add/operator/toPromise';

@Injectable({
  providedIn: 'root',
})
export class MediaService {
  constructor(private restangular: Restangular) {
  }

  search(params: any): Promise<any> {
    return this.restangular.one('media', 'search').get(params).toPromise();
  }
}
