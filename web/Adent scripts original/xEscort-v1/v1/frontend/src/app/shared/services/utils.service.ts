import { Injectable } from '@angular/core';
import { Subject } from 'rxjs/Subject';
import 'rxjs/add/operator/toPromise';

function convertMinsToHrsMins(minutes) {
  const h = Math.floor(minutes / 60);
  const m = minutes % 60;
  const hour = h < 10 ? '0' + h : h;
  const minute = m < 10 ? '0' + m : m;

  return hour + ':' + minute;
}

@Injectable({
  providedIn: 'root'
})
export class UtilsService {
  private appLoading = new Subject<any>();
  public appLoading$ = this.appLoading.asObservable();

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

  filterBooleanParams(params: any) {
    let value = {};
    Object.keys(params).forEach(key => {
      if (params[key] !== 'false' && params[key]) {
        value[key] = params[key] === 'true' || params[key] === true ? 1 : params[key];
      }
    });
    return value;
  }

  setLoading(loading: boolean) {
    this.appLoading.next(loading);
  }
}
