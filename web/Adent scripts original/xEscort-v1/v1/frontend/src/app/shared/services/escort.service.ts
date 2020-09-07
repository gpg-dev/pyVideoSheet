import { Injectable } from '@angular/core';
import { RestangularModule, Restangular } from 'ngx-restangular';
import 'rxjs/add/operator/toPromise';

function convertMinsToHrsMins (minutes) {
  const h = Math.floor(minutes / 60);
  const m = minutes % 60;
  const hour = h < 10 ? '0' + h : h;
  const minute = m < 10 ? '0' + m : m;

  return hour + ':' + minute;
}

@Injectable({
  providedIn: 'root'
})
export class EscortService {
  constructor(private restangular: Restangular) { }

  find(id: string): Promise<any> {
    return this.restangular.one('escorts', id).get().toPromise();
  }

  search(params: any): Promise<any> {
    return this.restangular.one('escorts', 'search').get(params).toPromise();
  }

  update(params: any): Promise<any> {
    return this.restangular.all('escorts').customPUT(params).toPromise();
  }

  languages(): Promise<any> {
    return this.restangular.one('languages').get().toPromise();
  }

  media(): Promise<any> {
    return this.restangular.one('escorts/media').get().toPromise();
  }

  removeMedia(id: string): Promise<any> {
    return this.restangular.one('media', id).customDELETE().toPromise();
  }

  sendMessage(escortId: string, content: string) {
    return this.restangular.one('messages').customPOST({ escortId, content }).toPromise();
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
