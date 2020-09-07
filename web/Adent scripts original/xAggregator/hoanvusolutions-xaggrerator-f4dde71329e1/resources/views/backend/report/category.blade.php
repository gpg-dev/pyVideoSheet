@extends('backend.layouts.main')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Top Category</h3>
          </div><!-- /.box-header -->
          <div class="box-body list-item">
            <br>
            <table class="table table-striped">
              <tr>
                <th>Title</th>
                <th>No. Clicks</th>
              </tr>                
              <?php                
              if(count($top_category)){
              foreach ($top_category as $item) { ?>
                <tr>                                                                   
                  <td><?php echo $item->title; ?></td>
                  <td><?php echo $item->numOfClicks; ?></td>
                </tr>
              <?php }
              }else{
                ?>
                <tr><td colspan="6">No item found</td></tr>                 
               <?php 
              }?>

            </table>
              <?php echo $top_category->render(); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection