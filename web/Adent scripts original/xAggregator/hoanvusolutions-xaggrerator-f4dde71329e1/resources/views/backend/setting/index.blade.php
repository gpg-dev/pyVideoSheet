@extends('backend.layouts.main')
@section('content')
<?php
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Settings
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
      echo Form::model($model, array('url' => action('Backend\SettingController@postUpdate'), 'enctype' => 'multipart/form-data'));      
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
              <h3 class="page-title"><i class="fa fa-cogs"></i> Settings Detail </h3>
          </div><!-- /.box-header -->      
          
          <div class="box-body list-item">
              <div class="form-group">
              <label class="control-label">Favicon: </label>             
              <?php if (isset($settings['favicon']) && $settings['favicon']) {
                echo "<br/>";
                if(strpos($settings['favicon'], 'http') !== false){
                    echo '<img src="' . $settings['favicon'] . '" style="width: auto;"/>';
                } else {
                    echo '<img src="' . URL::to('uploads/images/' . $settings['favicon']) . '" style="width: auto;"/>';
                }
              }?>
              <br/>
              <input type="file" name="favicon" accept="image/*"/>
            </div>
            <div class="form-group">
              <label class="control-label">Logo Header: </label>             
              <?php if (isset($settings['header_logo']) && $settings['header_logo']) {
                  echo "<br/>";
                if(strpos($settings['header_logo'], 'http') !== false){
                    echo '<img src="' . $settings['header_logo'] . '" style="width: auto;"/>';
                } else {
                    echo '<img src="' . URL::to('uploads/images/' . $settings['header_logo']) . '" style="width: auto;"/>';
                } 
              } ?>
              <br/>
              <input type="file" name="header_logo" accept="image/*"/>
            </div>
              <div class="form-group">
              <label class="control-label">Logo Footer: </label>             
              <?php if (isset($settings['footer_logo']) && $settings['footer_logo']) { 
                  echo "<br/>";
                if(strpos($settings['footer_logo'], 'http') !== false){
                    echo '<img src="' . $settings['footer_logo'] . '" style="width: auto;"/>';
                } else {
                    echo '<img src="' . URL::to('uploads/images/' . $settings['footer_logo']) . '" style="width: auto;"/>';
                } 
              }?>
              <br/>
              <input type="file" name="footer_logo" accept="image/*"/>
            </div>
            <div class="form-group">
                <label class="control-label">Title Page:<span class="color-red">*</span></label>
                <input type="text" name="title_page" class="form-control" maxlength="150" 
                       value="<?php echo isset($settings['title_page']) ? trim($settings['title_page']) : ''; ?>"/>       
            </div>
            <div class="form-group">
                <label class="control-label">Google Analytics Code:<span class="color-red">*</span></label>
                <textarea name="google_analytics" class="form-control" rows="5">
                    <?php echo isset($settings['google_analytics']) ? trim($settings['google_analytics']) : ''; ?>
                </textarea>
            </div>
            <div class="form-group">
                <label class="control-label">Footer Text:<span class="color-red">*</span></label>
                <input type="text" name="footer_text" class="form-control"
                       value="<?php echo isset($settings['footer_text']) ? trim($settings['footer_text']) : ''; ?>"/>   
            </div>
            <div class="form-group">
                <label class="control-label">Code before &lt;/body&gt; tag:<span class="color-red">*</span></label>
                <textarea name="before_body" class="form-control" rows="5">
                    <?php echo isset($settings['before_body']) ? trim($settings['before_body']) : ''; ?>
                </textarea>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <span class="caption-subject bold uppercase"> Meta Tags </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body" id="dv-meta-tags">
                        <?php if(count($metas) < 1){ ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Key: </label>
                                    <input type="text" name="key[]" class="form-control" maxlength="150" 
                                           value="<?php echo isset($settings['meta_description']) ? trim($settings['meta_description']) : ''; ?>"/>       
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <label class="control-label">Content: </label>
                                    <input type="text" name="content[]" class="form-control" maxlength="150" 
                                           value="<?php echo isset($settings['meta_description']) ? trim($settings['meta_description']) : ''; ?>"/>       
                                </div>
                            </div>
                        </div>
                        <?php }else{ ?>
                            <?php for($i=0; $i<count($metas); $i++){ ?>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">Key: </label>
                                        <input type="text" name="key[]" class="form-control" maxlength="150" 
                                               value="<?php echo $metas[$i]->key; ?>"/>       
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label class="control-label">Content: </label>
                                        <input type="text" name="content[]" class="form-control" maxlength="150" 
                                               value="<?php echo $metas[$i]->content; ?>"/>       
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="form-actions noborder">
                        <a class="btn blue" id="btn_add_meta">Add more</a>
                        <a class="btn default" id="btn_remove">Remove Meta</a>
                    </div>
                </div>
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
<script>
    $("#btn_add_meta").on('click', function(){
        var html = '<div class="row"> ' +
                '<div class="col-sm-3"> ' +
                '<div class="form-group"> ' +
                '<label class="control-label">Key: </label> ' +
                '<input type="text" name="key[]" class="form-control" maxlength="150"  ' +
                       'value="<?php echo isset($settings['meta_description']) ? $settings['meta_description'] : ''; ?>"/> ' +
                '</div> ' +
                '</div> ' +
                '<div class="col-sm-9"> ' +
                '<div class="form-group"> ' +
                '<label class="control-label">Content: </label> ' +
                '<input type="text" name="content[]" class="form-control" maxlength="150"  ' +
                       'value="<?php echo isset($settings['meta_description']) ? $settings['meta_description'] : ''; ?>"/> ' +
                '</div> ' +
                '</div> ' +
                '</div>';
        $("#dv-meta-tags").append(html);
    });
    
    $("#btn_remove").on('click', function(){
        $("#dv-meta-tags .row:last-child").remove();
    });
</script>
@endsection