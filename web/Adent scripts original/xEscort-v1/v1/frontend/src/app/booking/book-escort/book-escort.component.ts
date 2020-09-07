import { Component, OnInit, OnDestroy, Input } from '@angular/core';
import { Router, ActivatedRoute, ParamMap, NavigationEnd } from '@angular/router';
import { ToastyService } from 'ng2-toasty';
import { EscortService, BookingService } from '../../shared/services';
import { CheckoutModalComponent } from '../../payment/checkout-modal/checkout-modal.component';
import { NgbModal, NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  templateUrl: './book-escort.html'
})
export class BookEscortComponent implements OnInit {
  public escort: any;
  public booking: any = {
    startTime: 0
  };
  public minutes: any = [];
  public date: any = new Date();
  public minYear: any = this.date.getFullYear();
  public minMonth: any = this.date.getMonth() + 1;
  public minDate: any = this.date.getDate();

  constructor(private router: Router, private route: ActivatedRoute, private escortService: EscortService,
    private bookingService: BookingService, private toasty: ToastyService, private modalService: NgbModal) {
    let username = route.snapshot.params.username;
    escortService.find(username)
      .then(resp => {
        this.escort = resp.data.escort;
        this.booking.escortId = this.escort._id;
      });

    this.minutes = escortService.getMinutes();
  }

  ngOnInit() {
  }

  book(frm: any) {
    if (frm.invalid) {
      return this.toasty.error('Invalid form, please retry again.')
    }

    this.bookingService.create(Object.assign(this.booking, {
      date: new Date(this.date.year, this.date.month, this.date.day)
    }))
      .then(resp => {
        // do redirect to payment gateway
        const modalRef = this.modalService.open(CheckoutModalComponent)
        modalRef.componentInstance.data = {
          service: 'booking',
          booking: resp.data
        };
      });
  }
}
