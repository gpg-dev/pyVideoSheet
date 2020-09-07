import { Injectable } from '@angular/core';
import { RestangularModule, Restangular } from 'ngx-restangular';
import 'rxjs/add/operator/toPromise';

@Injectable()
export class InvoiceService {
  constructor(private restangular: Restangular) { }

  search(params: any): Promise<any> {
    return this.restangular.one('payment', 'invoices').get(params).toPromise();
  }

  findOne(id): Promise<any> {
    return this.restangular.one('payment/invoices', id).get().toPromise();
  }
}
