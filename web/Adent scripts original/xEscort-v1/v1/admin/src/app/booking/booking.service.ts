import { Injectable } from '@angular/core';
import { RestangularModule, Restangular } from 'ngx-restangular';
import 'rxjs/add/operator/toPromise';

@Injectable()
export class BookingService {
  constructor(private restangular: Restangular) { }

  search(params: any): Promise<any> {
    return this.restangular.one('booking').get(params).toPromise();
  }

  findOne(id): Promise<any> {
    return this.restangular.one('booking', id).get().toPromise();
  }
}
