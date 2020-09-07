@extends('backend.layouts.main')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Source Sites</h3>
          </div><!-- /.box-header -->
          <div class="box-body list-item">                                     
            <form action="" method="get">
              <div class="row">
                <div class="col-xs-3">
                    <input type="text" name="title" placeholder="Title" class="form-control" value="<?php echo $searchParams['title']?>"/>
                </div>
                <div class="col-xs-3">
                    <input type="text" name="link" placeholder="Link" class="form-control" value="<?php echo $searchParams['link']?>"/>
                </div>                                                
                <div class="col-xs-1">
                  <input type="submit" value="Search" class="btn btn-primary"/>
                </div>
              </div>
            </form>
            <br>
            <table class="table table-striped">
              <tr>
                <th><?php if(isset($_GET['order'])){
                        if($_GET['order']=='title'){
                            if(isset($_GET['sort']) && strtoupper($_GET['sort'])=='DESC'){
                                echo '<a href="' . action('Backend\SourceSiteController@index', $parameter_order . 'order=title') . 
                                        '"><i class="fa fa-caret-up"></i>';
                            }else{
                                echo '<a href="' . action('Backend\SourceSiteController@index', $parameter_order . 'order=title&sort=DESC') . 
                                        '"><i class="fa fa-caret-down"></i>';
                            }
                        }else{
                            echo '<a href="' . action('Backend\SourceSiteController@index', $parameter_order . 'order=title') . '">';
                        }
                    }else{ ?>
                    <a href="<?php echo action('Backend\SourceSiteController@index', $parameter_order . 'order=title&sort=DESC'); ?>">
                        <i class="fa fa-caret-down"></i>
                    <?php } ?>
                        Title
                    </a>
                </th>
                <th>Link</th>
                <th>Format CSV</th>
                <th>
                    <?php if(isset($_GET['order']) && $_GET['order']=='total'){
                        if(isset($_GET['sort']) && strtoupper($_GET['sort'])=='DESC'){
                            echo '<a href="' . action('Backend\SourceSiteController@index', $parameter_order . 'order=total') . 
                                    '"><i class="fa fa-caret-up"></i>';
                        }else{
                            echo '<a href="' . action('Backend\SourceSiteController@index', $parameter_order . 'order=total&sort=DESC') . 
                                    '"><i class="fa fa-caret-down"></i>';
                        }
                    }else{ ?>
                    <a href="<?php echo action('Backend\SourceSiteController@index', $parameter_order . 'order=total'); ?>">
                    <?php } ?>
                        No. Clicks
                    </a>
                </th>
                <th></th>
              </tr>                
              <?php                
              if(count($sourceSites)){
              foreach ($sourceSites as $item) { ?>
                <tr>                                                                   
                  <td><?php echo $item->title; ?></td>
                  <td><?php echo $item->link; ?></td>
                  <td><?php echo $item->formatCSVFrom; ?></td>
                  <td><?php echo $item->total ? $item->total: '0'; ?></td>
                  <td style="width: 53px;">
                    <a href="<?php echo action('Backend\SourceSiteController@getUpdate', ['id' => $item->id]);?>"><i class="fa fa-pencil"></i></a> | 
                    <!--<a href="<?php //echo action('Backend\SourceSiteController@getDelete', ['id' => $item->id]);?>" class="delete-item">
                        <i class="fa fa-trash"></i>
                    </a>-->
                  </td>
                </tr>
              <?php }
              }else{
                ?>
                <tr><td colspan="6">No item found</td></tr>                 
               <?php 
              }?>
            </table>
              <?php echo $sourceSites->render(); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
@section("script")
@endsection