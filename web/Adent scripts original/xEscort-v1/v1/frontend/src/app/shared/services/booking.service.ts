import { Injectable } from '@angular/core';
import { RestangularModule, Restangular } from 'ngx-restangular';
import 'rxjs/add/operator/toPromise';

@Injectable({
  providedIn: 'root'
})
export class BookingService {
  constructor(private restangular: Restangular) { }

  create(params: any): Promise<any> {
    return this.restangular.one('booking').customPOST(params).toPromise();
  }

  list(params: any): Promise<any> {
    return this.restangular.one('booking').get(params).toPromise();
  }
}
