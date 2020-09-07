import { Pipe, PipeTransform } from '@angular/core';
/*
 * convert minute to hours
 * Takes an exponent argument that defaults to 1.
 * Usage:
 *   value | m2h
*/
@Pipe({name: 'm2h'})
export class MinuteToHoursPipe implements PipeTransform {
  transform(minutes: number): string {
    const h = Math.floor(minutes / 60);
    const m = minutes % 60;
    const hour = h < 10 ? '0' + h : h;
    const minute = m < 10 ? '0' + m : m;

    return hour + ':' + minute;
  }
}
