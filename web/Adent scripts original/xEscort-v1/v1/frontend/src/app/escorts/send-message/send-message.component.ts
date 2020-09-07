import { Component, OnInit, OnDestroy, Input } from '@angular/core';
import { EscortService, AuthService } from '../../shared/services';

import { NgbModal, NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastyService } from 'ng2-toasty';

@Component({
  selector: 'ngbd-modal-content',
  template: `
    <div class="modal-header">
      <h4 class="modal-title">{{'PM to'|translate}} {{escort?.username}}</h4>
      <button type="button" class="close" aria-label="Close" (click)="activeModal.dismiss('Cross click')">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form class="form" #frm="ngForm" (submit)="send(frm)">
        <div class="form-group">
          <textarea [(ngModel)]="content.message" name="message" #message="ngModel"
            class="form-control"
            placeholder="{{'Enter your message'|translate}}"
            required></textarea>
          <div *ngIf="message.errors && (message.dirty || message.touched || submitted)">
            <p [hidden]="!message.errors.required" class="error" translate>Message is required</p>
          </div>
        </div>

        <button type="submit" class="btn btn-outline-dark" translate>Send</button>
      </form>
    </div>
  `
})
export class SendMessageModalComponent {
  @Input() escort;
  public content: any = {
    message: ''
  };
  public submitted: boolean = false;

  constructor(public activeModal: NgbActiveModal, private escortService: EscortService, private toasty: ToastyService) { }

  public send(frm: any) {
    this.submitted = true;
    if (frm.invalid) {
      return;
    }

    this.escortService.sendMessage(this.escort._id, this.content.message)
      .then(() => {
        this.toasty.success('Message has sent');
        this.activeModal.close();
      })
      .catch(() => this.toasty.error('Something went wrong, please try again later!'));
  }
}

@Component({
  selector: 'send-escort-message',
  template: `<button class="btn btn-primary" type="button" (click)="openModal()">{{'SEND PM'|translate}}</button>`
})
export class SendMessageComponent implements OnInit {
  @Input() escort;

  constructor(private modalService: NgbModal, private authService: AuthService, private toasty: ToastyService) { }

  ngOnInit() { }

  openModal() {
    if (!this.authService.isLoggedin()) {
      return this.toasty.error('Please login!');
    }

    const modalRef = this.modalService.open(SendMessageModalComponent);
    modalRef.componentInstance.escort = this.escort;
  }
}
