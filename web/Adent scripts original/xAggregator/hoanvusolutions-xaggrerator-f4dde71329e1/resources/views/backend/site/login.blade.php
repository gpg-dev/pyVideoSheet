@extends('backend.layouts.login')
@section('content')
<div class="row">
  <div class="login-container">
    <div class="signin">
      <div id="logo">
        xAggrerator
      </div>				
      <div class="tab-content">
        <div id="login" class="tab-pane active">
          {{Form::open(array('url' => action('Backend\SiteController@postLogin'), 'id' => 'login-form', 'class' => 'form-vertical')) }}         
          
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
  
          <div class="control-group">
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on"><i class="icon-user"></i></span>									                
                <div class="form-group">
                  <label class="control-label" for="loginmodel-email">Email</label>                 
                  <?php echo Form::email('email', $value = null, array('class' => 'form-control','placeholder' => 'Enter Email')) ?>                  
                </div>               
              </div>
            </div>
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on"><i class="icon-lock"></i></span>									
                <div class="form-group">
                  <label class="control-label" for="loginmodel-email">Password</label>                                   
                  <?php echo Form::password('password',array('placeholder' => 'Enter Password', 'class' => 'form-control'))?>
                </div>
                
              </div>
            </div>            
          </div>
          <input type="submit" value="Log in" class="btn btn-primary" name="login-button"/>              
          {{Form::close()}}
        </div>
      </div>				
    </div>
  </div>
</div>
@endsection