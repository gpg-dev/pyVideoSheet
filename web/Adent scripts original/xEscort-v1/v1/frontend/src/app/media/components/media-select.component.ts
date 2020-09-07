import { Component, OnInit, EventEmitter, Input, Output } from '@angular/core';
import { NgbModal, NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { AuthService } from '../../shared/services';
import { MediaService } from '../service';

@Component({
  selector: 'media-modal',
  templateUrl: './media-modal.html'
})
export class MediaModalComponent implements OnInit {
  @Input() options: any;
  public uploadedFiles = [];
  public tab: String = 'library';
  public files = [];
  public page: any = 1;
  public totalMedia = 0;
  public hasBaseDropZoneOver: boolean = false;
  public filesQueue: any = [];
  public fileSelectOptions: any;

  // TODO - define active tab

  constructor(public activeModal: NgbActiveModal, private authService: AuthService, private mediaService: MediaService) {
  }
  ngOnInit() {
    if (!this.options) {
      this.options = {};
    }
    this.fileSelectOptions = Object.assign(this.options, {
      onCompleteItem: (resp) => this.uploadedFiles.push(resp.data),
      onFileSelect: this.onFileSelect.bind(this)
    });
  }

  ngAfterViewInit() {
    this.loadLibrary();
  }

  getPreview(file: any) {
    if (file.type) {
      // do nothing if it already set
      return;
    }

    if (file.file.type.indexOf('image') > -1) {
      file.type = 'photo';

      const reader = new FileReader();
      reader.onload = (e: any) => file.previewContent = e.target.result;

      // read the image file as a data URL.
      reader.readAsDataURL(file._file);
    } else if (file.file.type.indexOf('video') > -1) {
      file.type = 'video';
    } else {
      file.type = 'file';
    }
  }

  onFileSelect(queue) {
    const _this = this;
    this.filesQueue = queue;
    this.filesQueue.forEach((q: any) => _this.getPreview(q));
  }

  remove(item: any) {
    this.fileSelectOptions.uploader.removeFromQueue(item);
  }

  select(media: any) {
    this.activeModal.close(media);
  }

  loadLibrary() {
    this.mediaService.search(Object.assign({
      page: this.page
    }, (this.options && this.options.query ? this.options.query : {})))
      .then(resp => {
        this.files = this.files.concat(resp.data.items);
        this.totalMedia = resp.data.count;
      });
  }

  changeTab(tab) {
    if (this.tab !== 'library' && tab == 'library') {
      this.files = [];
      this.loadLibrary();
    }

    this.tab = tab;
  }
}

@Component({
  selector: 'media-select',
  template: `<span class="pointer media-select-btn" (click)="open()">{{'Select file'|translate}}</span>`
})
export class FileSelectComponent {
  @Output() onSelect = new EventEmitter();
  /**
   * option format
   * {
   *  customFields: { key: value } // additional field will be added to the form
   *  query: { key: value } // custom query string
   * }
   */
  @Input() options: any;
  constructor(private modalService: NgbModal) { }

  open() {
    const modalRef = this.modalService.open(MediaModalComponent, {
      size: 'lg' // TODO - custom class here for larger screen
    });

    modalRef.componentInstance.options = this.options;
    modalRef.result.then(result => this.onSelect.emit(result), () => (null));
  }
}
