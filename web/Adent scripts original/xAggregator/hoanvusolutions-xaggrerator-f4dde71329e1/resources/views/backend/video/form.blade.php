@extends('backend.layouts.main')
@section("css")
<link rel="stylesheet" href="{{URL::asset('backends/plugins/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('backends/plugins/select2/select2-bootstrap.min.css')}}">
@endsection
@section('content')
<?php

use App\Models\Venue; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Video
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
    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>      
      <ul>
        <li>{{ Session::get('success') }}</li>
      </ul>              
    </div>    
    @endif
    <?php
    if ($model->id) {
      echo Form::model($model, array('url' => action('Backend\VideoController@postUpdate', ['id' => $model->id]), 'enctype' => 'multipart/form-data', 'id' => 'videoForm'));
    } else {
      echo Form::model($model, array('url' => action('Backend\VideoController@postCreate'), 'enctype' => 'multipart/form-data', 'id' => 'videoForm'));
    }
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">
              Video Detail
            </h3>
          </div><!-- /.box-header -->      

          <div class="box-body">                            
            <div class="form-group">
              <label class="control-label">Avatar of Video</label>             
              <?php if ($model->getImage()) { ?>
                <br>
                <img src="<?php echo $model->getImage(); ?>" style="width: 200px;"/>
              <?php } ?>
              <br><br>
              <input type="file" name="file" accept="image/*"/>
            </div>
            <div class="form-group">
              <label class="control-label">Url <span class="color-red">*</span></label>              
              <?php echo Form::text('url', null, array('class' => 'form-control', 'placeholder' => 'Enter Video Url', 'maxlength' => 150)) ?>              
            </div>
            <div class="form-group">
              <label class="control-label">Title <span class="color-red">*</span></label>              
              <?php echo Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Enter Video Title', 'maxlength' => 150)) ?>              
            </div>
            <div class="form-group">
              <label class="control-label">Description</label>              
              <?php echo Form::textarea('description', null, array('class' => 'form-control', 'row' => '5', 'placeholder' => 'Enter Venue Description')) ?>
            </div>

            <div class="form-group">
              <label for="categories" class="control-label">Categories </label>              

              <select name="category[]" id="categories" multiple class="form-control select2-multiple">
                <option value="">Select category</option>
                <?php foreach ($categories as $item) { ?>
                  <option value="<?php echo $item->id ?>" <?php if (isset($selectedCat) && array_search($item->id, $selectedCat) !== false) echo 'selected'; ?>><?php echo $item->title ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label for="tags" class="control-label">Tags </label>
              <select name="tags[]" id="tags" multiple class="form-control select2-multiple">
                <option value="">Select tag</option>
                <?php foreach ($tags as $item) { ?>
                  <option value="<?php echo $item->id ?>" <?php if (isset($selectedTags) && array_search($item->id, $selectedTags) !== false) echo 'selected'; ?>><?php echo $item->title ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label class="control-label">Duration</label>              
              <?php
              $hour = $minute = $second = 0;
              if ($model->duration) {
                $hour = date('H', $model->duration);
                $minute = date('i', $model->duration);
                $second = date('s', $model->duration);
              }
              ?>
              <div class="row">
                <div class="col-md-2">
                  <select name="hour" class="form-control">
                    <option value=""> Select Hour</option>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                      <option value="<?php echo $i ?>" <?php if ($hour == $i) echo 'selected' ?>><?php echo $i ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="minute" class="form-control">
                    <option value=""> Select Minute</option>
                    <?php for ($i = 0; $i <= 60; $i++) { ?>
                      <option value="<?php echo $i ?>" <?php if ($minute == $i) echo 'selected' ?>><?php echo $i ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="second" class="form-control">
                    <option value=""> Select Second</option>
                    <?php for ($i = 0; $i <= 60; $i++) { ?>
                      <option value="<?php echo $i ?>" <?php if ($second == $i) echo 'selected' ?>><?php echo $i ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
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
<script src="{{URL::asset('backends/plugins/select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var placeholder = "Select a State";
        $(".select2, .select2-multiple").select2({
            placeholder: placeholder,
            width: null
        });
        
        $(".select2, .select2-multiple, .select2-allow-clear, .js-data-example-ajax").on("select2:open", function() {
            if ($(this).parents("[class*='has-']").length) {
                var classNames = $(this).parents("[class*='has-']")[0].className.split(/\s+/);

                for (var i = 0; i < classNames.length; ++i) {
                    if (classNames[i].match("has-")) {
                        $("body > .select2-container").addClass(classNames[i]);
                    }
                }
            }
        });
    });
</script>
@endsection