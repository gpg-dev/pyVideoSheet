import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { Router } from '@angular/router';
import { NgbModal, NgbModalOptions } from '@ng-bootstrap/ng-bootstrap';
import { AuthService } from '../services';
import { ToastyService } from 'ng2-toasty';
import { RatingModalComponent } from './rating-modal.component';

@Component({
  selector: 'rating-button',
  template: `<a class="btn btn-danger btn-sm color-fff" (click)="rateModal()" title="rating">Add a review</a>`
})

export class RatingButtonComponent implements OnInit {
  @Input() escortId: any;
  @Output() onRating = new EventEmitter();
  public currentUser: any;

  constructor(private router: Router, private authService: AuthService, private modalService: NgbModal, private toasty: ToastyService) {}

  ngOnInit() {
  }

  rateModal() {
    if (!this.authService.isLoggedin()) {
      return this.toasty.error('Please login to add a review.')
    }
    let ngbModalOptions: NgbModalOptions = {
        backdrop : 'static',
        keyboard : false
      };
    const modalRef = this.modalService.open(RatingModalComponent, ngbModalOptions);
    modalRef.componentInstance.escortId  =  this.escortId;
    modalRef.result.then(data => this.onRating.emit(data), () => {});

  }

}
