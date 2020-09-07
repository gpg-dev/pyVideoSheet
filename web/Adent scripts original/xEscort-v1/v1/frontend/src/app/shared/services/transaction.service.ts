import { Injectable } from '@angular/core';
import { RestangularModule, Restangular } from 'ngx-restangular';
import 'rxjs/add/operator/toPromise';

@Injectable({
  providedIn: 'root'
})
export class TransactionService {
  constructor(private restangular: Restangular) { }

  request(params: any): Promise<any> {
    return this.restangular.one('payment/transactions', 'request').customPOST(params).toPromise();
  }

  invoices(params: any): Promise<any> {
    return this.restangular.one('payment/invoices').get(params).toPromise();
  }
}
