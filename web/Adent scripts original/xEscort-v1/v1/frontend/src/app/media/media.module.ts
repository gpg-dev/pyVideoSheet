import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { FileUploadModule } from 'ng2-file-upload/file-upload/file-upload.module';
import { FileSelectComponent, MediaModalComponent } from './components/media-select.component';
import { FileUploadComponent } from './components/file-upload.component';
import { MediaService } from './service';
import { UtilsModule } from '../utils/utils.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    NgbModule,
    FileUploadModule,
    UtilsModule
  ],
  exports: [
    FileSelectComponent,
    FileUploadComponent
  ],
  declarations: [
    FileSelectComponent,
    FileUploadComponent,
    MediaModalComponent
  ],
  entryComponents: [
    MediaModalComponent
  ],
  providers: [
    MediaService
  ]
})

export class MediaModule { }
