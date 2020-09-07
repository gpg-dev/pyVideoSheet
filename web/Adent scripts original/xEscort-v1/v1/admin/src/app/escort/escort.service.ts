import { Injectable } from '@angular/core';
import { Restangular } from 'ngx-restangular';
import 'rxjs/add/operator/toPromise';

function convertMinsToHrsMins(minutes) {
  const h = Math.floor(minutes / 60);
  const m = minutes % 60;
  const hour = h < 10 ? '0' + h : h;
  const minute = m < 10 ? '0' + m : m;

  return hour + ':' + minute;
}

@Injectable()
export class EscortService {
  constructor(private restangular: Restangular) { }

  create(credentials: any): Promise<any> {
    return this.restangular.all('escorts').post(credentials).toPromise();
  }

  search(params: any): Promise<any> {
    return this.restangular.one('escorts', 'search').get(params).toPromise();
  }

  findOne(id): Promise<any> {
    return this.restangular.one('escorts', id).get().toPromise();
  }

  update(id, data): Promise<any> {
    return this.restangular.one('escorts', id).customPUT(data).toPromise();
  }

  delete(id): Promise<any> {
    return this.restangular.one('users', id).customDELETE().toPromise();
  }

  languages(): Promise<any> {
    return this.restangular.one('languages').get().toPromise();
  }

  getMinutes() {
    const results = [];
    for (let i = 0; i <= 1440; i += 15) {
      results.push({
        value: i,
        text: convertMinsToHrsMins(i)
      });
    }
    return results;
  }
}
