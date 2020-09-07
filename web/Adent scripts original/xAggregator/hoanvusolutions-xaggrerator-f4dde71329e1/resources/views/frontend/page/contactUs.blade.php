@extends('frontend.layouts.main')
@section('content')
<div class="content">
    <div class="container">
        <div class='cover-static-page'>
            <h2>Contact Us</h2>
            <div class='content-contact-us'>
                <div class='row'>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Contact Us <span>*</span></label> 
                            <?php echo Form::text('email', null, array('class' => 'form-control','placeholder'=>'Enter your email')) ?>              
                        </div>
                        <div class="form-group">
                            <label class="control-label">Username <span>*</span></label> 
                            <?php echo Form::text('username', null, array('class' => 'form-control','placeholder'=>'Enter your username.')) ?>              
                        </div>
                        <div class="form-group">
                            <label class="control-label">Name </label> 
                            <?php echo Form::text('name', null, array('class' => 'form-control','placeholder'=>'Enter your name.')) ?>              
                        </div>
                        <div class="form-group">
                            <label class="control-label">What can we help you with </label> 
                            <?php echo Form::select('type', $contact_type, null, array('class' => 'form-control')) ?>              
                        </div>
                        <div class="form-group">
                            <label class="control-label">Message <span>*</span></label> 
                            <?php echo Form::textarea('message', null, array('class' => 'form-control')) ?>              
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    
@endsection