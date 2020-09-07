@extends('backend.layouts.main')
@section('content')
<?php
use App\Services\DefineConfig;
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Categories</h3>
          </div><!-- /.box-header -->      

          
          <div class="box-body list-item">                                     
            <form action="" method="get">
              <div class="row">
                <div class="col-xs-2">
                    <input type="text" name="title" placeholder="Title" class="form-control" value="<?php echo $searchParams['title']?>"/>
                </div>                                                
                <div class="col-xs-1">
                  <input type="submit" name="submit" value="Search" class="btn btn-primary"/>
                </div>
              </div>
            </form>
            <br>
            <table class="table table-striped">
              <tr>       
                <th></th>
                <th>
                    <?php if(isset($_GET['order'])){
                        if($_GET['order']=='title'){
                            if(isset($_GET['sort']) && strtoupper($_GET['sort'])=='DESC'){
                                echo '<a href="' . action('Backend\CategoryController@index', $parameter_order . 'order=title') . 
                                        '"><i class="fa fa-caret-up"></i>';
                            }else{
                                echo '<a href="' . action('Backend\CategoryController@index', $parameter_order . 'order=title&sort=DESC') . 
                                        '"><i class="fa fa-caret-down"></i>';
                            }
                        }else{
                            echo '<a href="' . action('Backend\CategoryController@index', $parameter_order . 'order=title') . '">';
                        }
                    }else{ ?>
                    <a href="<?php echo action('Backend\CategoryController@index', $parameter_order . 'order=title&sort=DESC'); ?>">
                        <i class="fa fa-caret-down"></i>
                    <?php } ?>
                        Title
                    </a>
                </th>          
                <th>
                    <?php if(isset($_GET['order']) && $_GET['order']=='numOfClicks'){
                        if(isset($_GET['sort']) && strtoupper($_GET['sort'])=='DESC'){
                            echo '<a href="' . action('Backend\CategoryController@index', $parameter_order . 'order=numOfClicks') . 
                                    '"><i class="fa fa-caret-up"></i>';
                        }else{
                            echo '<a href="' . action('Backend\CategoryController@index', $parameter_order . 'order=numOfClicks&sort=DESC') . 
                                    '"><i class="fa fa-caret-down"></i>';
                        }
                    }else{ ?>
                    <a href="<?php echo action('Backend\CategoryController@index', $parameter_order . 'order=numOfClicks'); ?>">
                    <?php } ?>
                        No. Clicks
                    </a>
                </th>          
                <th></th>
              </tr>                
              <?php                
              if(count($cats)){
              foreach ($cats as $item) { ?>
                <tr>                                    
                  <td><img src="<?php echo URL::to('/') . $item->getImage() ?>" class="img-represent"/></td>                                                                     
                  <td><?php  echo $item->title ?></td>
                  <td><?php echo $item->numOfClicks ? $item->numOfClicks: '0'; ?></td>
                  <td><?php echo $item->isActive == 1?'Active':'Inactive'; ?></td>
                  <td style="width: 53px;">
                    <a href="<?php echo action('Backend\CategoryController@getUpdate', ['id' => $item->id]);?>"><i class="fa fa-pencil"></i></a> | 
                    <a href="<?php echo action('Backend\CategoryController@getDelete', ['id' => $item->id]);?>" class="delete-item"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              <?php }
              }else{
                ?>
                <tr><td colspan="4">No item found</td></tr>                 
               <?php 
              }?>

            </table>
              <?php echo $cats->render(); ?>
          </div>                

        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@section("script")

@endsection