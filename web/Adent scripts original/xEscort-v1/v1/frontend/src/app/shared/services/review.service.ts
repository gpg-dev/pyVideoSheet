import { Injectable } from '@angular/core';
import { RestangularModule, Restangular } from 'ngx-restangular';
import 'rxjs/add/operator/toPromise';

@Injectable({
  providedIn: 'root'
})
export class ReviewService {
  constructor(private restangular: Restangular) { }

  create(params: any): Promise<any> {
    return this.restangular.one('reviews').customPOST(params).toPromise();
  }

  update(params: any): Promise<any> {
    return this.restangular.all('reviews').customPUT(params).toPromise();
  }

  list(params: any): Promise<any> {
    return this.restangular.one('reviews').get(params).toPromise();
  }
}
