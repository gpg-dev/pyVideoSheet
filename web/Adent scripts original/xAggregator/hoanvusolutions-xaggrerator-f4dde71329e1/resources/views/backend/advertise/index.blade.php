@extends('backend.layouts.main')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Advertises</h3>
          </div><!-- /.box-header -->
          <div class="box-body list-item">
            <form action="" method="get">
              <div class="row">
                <div class="col-xs-2">
                    <input type="text" name="name" placeholder="Name" class="form-control" value="<?php echo $searchParams['name']?>"/>
                </div>                                                
                <div class="col-xs-1">
                  <input type="submit" value="Search" class="btn btn-primary"/>
                </div>
              </div>
            </form>
            <br>
            <table class="table table-striped">
              <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Type</th>
                <th>Sort</th>
                <th>Status</th>
                <th></th>
              </tr>                
              <?php                
              if(count($advertises)){
              foreach ($advertises as $item) { ?>
                <tr>                                                                   
                  <td><?php echo $item->name; ?></td>
                  <td><?php echo $item->slug; ?></td>
                  <td><?php echo $item->type ? \App\Services\DefineConfig::positionAd[$item->type]: ''; ?></td>
                  <td><?php echo $item->sort; ?></td>
                  <td><?php echo $item->isActive == 1? 'Active': 'Inactive'; ?></td>
                  <td style="width: 53px;">
                    <a href="<?php echo action('Backend\AdvertiseController@getUpdate', ['id' => $item->id]);?>"><i class="fa fa-pencil"></i></a> | 
                    <a href="<?php echo action('Backend\AdvertiseController@getDelete', ['id' => $item->id]);?>" class="delete-item"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              <?php }
              }else{
                ?>
                <tr><td colspan="2">No item found</td></tr>                 
               <?php 
              }?>
            </table>
              <?php echo $advertises->render(); ?>
          </div>                

        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@section("script")

@endsection