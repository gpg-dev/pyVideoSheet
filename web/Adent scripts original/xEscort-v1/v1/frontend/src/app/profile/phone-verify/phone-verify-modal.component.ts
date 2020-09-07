import {Component, Input} from '@angular/core';

import { NgbModal, NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { AuthService } from '../../shared/services';

@Component({
  selector: 'phone-verify-modal-content',
  templateUrl: './phone-verify.html'
})
export class PhoneVerifyModal {
  public verificationCode: string = '';
  public confirmCode: boolean = false;
  public dialCode: string = '+1';
  public dialCodes: any = [];
	public flag: string = '/assets/images/flags/us.svg';
  public phoneNumber: string = '';
  public open: boolean = false;
  public submitted: boolean = false;

  constructor(public activeModal: NgbActiveModal, private Auth: AuthService) {
    this.dialCodes = this.Auth.getDialCodes();
  }
  
  confirm() {
    this.Auth.verifyPhone({
      phoneNumber: `${this.dialCode}${this.phoneNumber}`,
      code: this.verificationCode
    })
    .then(resp => this.activeModal.close(`${this.dialCode}${this.phoneNumber}`))
    .catch(e => alert('Invalid code, please try again!'));
  }
  
  changePhone(frm: any) {
    this.submitted = true;
    
    if (frm.invalid) {
      return;
    }

    this.Auth.changePhone({
      phoneNumber: `${this.dialCode}${this.phoneNumber}`,
      dialCode: this.dialCode
    })
      .then(resp => this.confirmCode = true)
      .catch(e => alert('Something went wrong, please try again!'));
  }
  
  selectDial(dial: any) {
    this.dialCode = dial.dialCode;
		this.flag = `/assets/images/flags/${dial.code.toLowerCase()}.svg`;
    this.open = false;
  }
}
