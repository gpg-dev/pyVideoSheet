@extends('backend.layouts.main')
@section('content')
<?php
use App\Services\DefineConfig;
use App\Models\Venue;
?>

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
    
    <?php
    if($model->id){
      echo Form::model($model, array('url' => action('Backend\CategoryController@postUpdate', ['id' => $model->id]), 'enctype' => 'multipart/form-data'));      
    }else{
      echo Form::model($model, array('url' => action('Backend\CategoryController@postCreate'), 'enctype' => 'multipart/form-data'));
    }
    ?>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">
              Category Detail
            </h3>
          </div><!-- /.box-header -->      
          
          <div class="box-body">
            <div class="form-group">
              <label class="control-label">Image</label>             
              <?php if ($model->getImage()) { ?>
                <br>
                <img src="<?php echo URL::to('/') . $model->getImage(); ?>" style="width: 150px;"/>
              <?php } ?>
              <br><br>
              <input type="file" name="file" accept="image/*"/>
            </div>
            <div class="form-group">
              <label class="control-label">Title <span class="color-red">*</span></label>              
              <?php echo Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Enter Video Title','maxlength'=>150)) ?>              
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