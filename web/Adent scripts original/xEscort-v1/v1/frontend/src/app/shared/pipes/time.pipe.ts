import { Pipe, PipeTransform } from '@angular/core';

/*
 * format time 00:00
*/
@Pipe({
  name: 'time'
})
export class TimePipe implements PipeTransform {
  constructor() {}

  transform(value: any): any {
    if (!value) {
      return '00:00';
    }

    let integer = (value / 60).toFixed(0);

    return integer + ':' + (value % 60);
  }
}
