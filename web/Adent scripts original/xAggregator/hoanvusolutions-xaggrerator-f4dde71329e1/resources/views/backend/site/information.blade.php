@extends('backend.layouts.main')
@section('content')
<?php

use App\Models\Venue; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Information <small>User</small>
    </h1>  
  </section>
  <section class="content">
    @if(Session::has('msg'))
    <div class="alert alert-success alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      <p>{{Session::get('msg')}}</p>
    </div>    
    @endif

    @if(!$errors->isEmpty())
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>      
      <ul>
        @foreach($errors->all() as $item)
        <li>{{$item}}</li>
        @endforeach
      </ul>              
    </div>    
    @endif
    @if(isset($success) && $success)
    <div class="alert alert-success alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>      
      <ul>
        <li>{{$success}}</li>
      </ul>              
    </div>    
    @endif
    <?php
        echo Form::model($user, array('url' => action('Backend\SiteController@postInforUser'), 'enctype' => 'multipart/form-data'));
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Email <span class="color-red">*</span></label>              
              <?php echo Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Enter Page Title','maxlength'=>150)) ?>              
            </div>
          </div>
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Old Password <span class="color-red">*</span></label>              
              <?php echo Form::password('old_password', array('class' => 'form-control')) ?>              
            </div>
          </div>
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Password <span class="color-red">*</span></label>              
              <?php echo Form::password('password', array('class' => 'form-control')) ?>              
            </div>
          </div>
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Password Confirm <span class="color-red">*</span></label>
              <?php echo Form::password('password_confirmation', array('class' => 'form-control')) ?>
            </div>
          </div>
          <div class="box-footer">
            <input type="submit" class="btn btn-primary" value="Submit"/>
          </div>
        </div>
      </div>
    </div>
    <?php echo Form::close() ?>
  </section>
</div>
@endsection

@section("script")

@endsection