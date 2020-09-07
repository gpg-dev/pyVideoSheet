import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { SystemService } from '../services';
import { TranslateService } from '@ngx-translate/core';
declare var $: any;

@Component({
  selector: 'app-foot',
  templateUrl: './footer.html'
})
export class FooterComponent implements OnInit {
  public appConfig: any;
  public languages: any = [];
  public userLang: string = 'en';
  constructor(private route: ActivatedRoute, private systemService: SystemService, private translate: TranslateService) {
    systemService.configs().then(resp => {
      if (resp) {
        this.languages = resp.i18n.languages;
        this.userLang = resp.userLang;
      }
      this.appConfig = resp;
    });
  }

  changeLang(lang: string) {
    this.systemService.setUserLang(lang);
    this.translate.use(lang);
    this.userLang = lang;
  }

  ngOnInit() { }

  goTop() {
    $('html, body').animate({ scrollTop: 0 });
    return false;
  }
}
