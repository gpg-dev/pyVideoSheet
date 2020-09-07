import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ConfigRoutingModule } from './config.routing';
import { ConfigsComponent } from './list/configs.component';
import { ConfigService } from './service';

import { MediaModule } from '../media/media.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    //our custom module
    ConfigRoutingModule,
    MediaModule
  ],
  declarations: [
    ConfigsComponent
  ],
  providers: [ConfigService]
})
export class ConfigModule { }
