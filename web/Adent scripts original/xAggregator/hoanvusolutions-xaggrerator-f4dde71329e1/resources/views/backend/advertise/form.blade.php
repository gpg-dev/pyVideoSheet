@extends('backend.layouts.main')
@section('content')
<?php

use App\Models\Venue; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Advertise
      <small>
        <?php if ($model->id) { ?>
          Update
        <?php } else { ?>
          Create 
        <?php } ?>
      </small>
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
    if($model->id){
      echo Form::model($model, array('url' => action('Backend\AdvertiseController@postUpdate', ['id' => $model->id]), 'enctype' => 'multipart/form-data'));      
    }else{
      echo Form::model($model, array('url' => action('Backend\AdvertiseController@postCreate'), 'enctype' => 'multipart/form-data'));
    }
    ?>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">
              Advertise Detail
            </h3>
          </div><!-- /.box-header -->
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Name <span class="color-red">*</span></label>              
              <?php echo Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Enter Page Title','maxlength'=>150)) ?>              
            </div>
          </div>
          <?php if($model->id){ ?>
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Slug <span class="color-red">*</span></label>              
              <?php echo Form::text('slug', null, array('class' => 'form-control','disabled'=>'disabled')) ?>              
            </div>
          </div>
          <?php } ?>
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Type<span class="color-red">*</span></label>
              <?php echo Form::select('type', \App\Services\DefineConfig::positionAd, null, array('class' => 'form-control')) ?>
            </div>
          </div>
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Content (Link Image / Script Code)<span class="color-red">*</span></label>              
              <?php echo Form::textarea('content', null, array('class' => 'form-control', 'placeholder' => 'Enter Content Page')) ?>              
            </div>
          </div>
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Sort</label>              
              <?php echo Form::text('sort', null, array('class' => 'form-control')) ?>              
            </div>
          </div>
          <div class="box-body list-item">
            <div class="form-group">
              <label class="control-label">Is Active</label>              
              <?php echo Form::select('isActive', array('1' => 'Active', '2' => 'Inactive'), null, array('class' => 'form-control')) ?>              
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