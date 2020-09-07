import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { AuthService } from '../services';
import { ReviewService } from '../services';
import { ToastyService } from 'ng2-toasty';

@Component({
  selector: 'rating-modal',
  templateUrl: './rating-modal.html'
})
export class RatingModalComponent implements OnInit {
  @Input() escortId: any;
  public selectedStar: number = 3;
  public hovered: number;
  public comment: string = '';

  constructor(public activeModal: NgbActiveModal, private authService: AuthService,
     private toasty: ToastyService, private reviewService: ReviewService) {}

  ngOnInit() {
  }

  rateStar() {
    this.reviewService.create({userId: this.escortId, rating: this.selectedStar, comment: this.comment}).then((resp) => {
      this.activeModal.close(resp.data);
      this.toasty.success('Thanks.')
    })
    .catch((err) => this.toasty.error('Something went wrong, please retry later'))
  }

}
