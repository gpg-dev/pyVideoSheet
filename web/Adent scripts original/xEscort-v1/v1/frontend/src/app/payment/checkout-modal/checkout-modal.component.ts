import { Component, Input, OnInit } from '@angular/core';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { TransactionService, SystemService, UtilsService } from '../../shared/services';

@Component({
  templateUrl: './checkout-modal.html'
})
export class CheckoutModalComponent implements OnInit {
  @Input() data: any;
  public transaction: any = {
    service: 'booking',
    gateway: 'paypal',
    redirectSuccessUrl: `${window.appConfig.appBaseUrl}/payment/result/success`,
    redirectCancelUrl: `${window.appConfig.appBaseUrl}/payment/result/cancelled`
  };
  public bookingPrice: any = 10;
  public loading: boolean = false;

  constructor(public activeModal: NgbActiveModal,
    private transactionService: TransactionService,
    private systemService: SystemService,
    private utilService: UtilsService) {
    systemService.configs().then(resp => {
      this.bookingPrice = resp.bookingPrice;
    });
  }

  ngOnInit() {
    this.transaction.service = this.data.service;
    if (this.data.service === 'booking') {
      this.transaction.itemId = this.data.booking._id;
    }
  }

  pay() {
    this.utilService.setLoading(true);
    this.transactionService.request(this.transaction)
      .then(resp => {
        window.location.href = resp.data.redirectUrl;
      })
      .catch(() => this.utilService.setLoading(false));
  }
}
