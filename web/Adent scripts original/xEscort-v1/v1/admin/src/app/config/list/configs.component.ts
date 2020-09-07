import { Component, OnInit } from '@angular/core';
import { ConfigService } from '../service';
import { ToastyService } from 'ng2-toasty';

@Component({
  selector: 'configs',
  templateUrl: './configs.html'
})
export class ConfigsComponent implements OnInit {
  public items = [];
  constructor(private configService: ConfigService, private toasty: ToastyService) {
  }

  ngOnInit() {
    this.query();
  }

  query() {
    this.configService.list()
      .then(resp => this.items = resp.data.items)
      .catch(() => this.toasty.error('Something went wrong, please try again!'));
  }

  save(item: any) {
    if (item.type === 'number' && item.value < 0) {
      return this.toasty.error('Please enter positive number!')
    }

    this.configService.update(item._id, item.value)
      .then(() => this.toasty.success('Updated successfully!'))
      .catch(e => this.toasty.error('Something went wrong, please try again!'));
  }

  selectFile(event: any, item: any) {
    item.value = event.fileUrl;
  }
}
