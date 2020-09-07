@extends('backend.layouts.main')
@section('content')
<div class="content-wrapper">
    <section class="content">
        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <ul>
                <li>{{ Session::get('success') }}</li>
            </ul>
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-error alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <ul>
                <li>{{ Session::get('error') }}</li>
            </ul>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Videos</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body list-item">
                        <br>
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-xs-2"><input type="text" name="title" placeholder="Video Title" class="form-control" value="<?php echo $searchParams['title']; ?>"/></div>
                                <div class="col-xs-2">
                                    <select name="category" class="form-control">
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $item) { ?>
                                            <option <?php if ($searchParams['category'] == $item->id) echo 'selected' ?> value="<?php echo $item->id; ?>"><?php echo $item->title; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-xs-1">
                                    <input type="submit" name="submit" value="Search" class="btn btn-primary"/>
                                </div>
                                <div class="col-xs-7">
                                    <a class="btn btn-warning pull-right" id="delete-all">DELETE ALL</a>
                                </div>
                            </div>
                        </form>
                        <br>
                        <form method="post" action="<?php echo action('Backend\VideoController@postDelete'); ?>" id="frm-table" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <table class="table table-striped">
                                <tr>
                                    <th><input type="checkbox" id="chk-check-all" /></th>
                                    <th></th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Duration</th>
                                    <th>Number of Clicks</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                <?php
                                if (count($videos)) {
                                    foreach ($videos as $item) {
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" class="chk-ids" name="chk_ids[]" value="<?php echo $item->id; ?>"/></td>
                                            <td><img src="<?php echo $item->getImage(); ?>" class="img-represent"/></td>
                                            <td><?php echo $item->title; ?></td>
                                            <td><?php echo $item->getCatName(); ?></td>
                                            <td><?php echo date("H:i:s", $item->duration); ?></td>
                                            <td><?php echo $item->numOfClicks; ?></td>
                                            <td><?php echo $item->isActive == 1 ? 'Active' : 'Inactive'; ?></td>
                                            <td style="width: 53px;">
                                                <a href="<?php echo action('Backend\VideoController@getUpdate', ['id' => $item->id]); ?>"><i class="fa fa-pencil"></i></a> |
                                                <a href="<?php echo action('Backend\VideoController@getDelete', ['id' => $item->id]); ?>" class="delete-item"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr><td colspan="6">No item found</td></tr>
                                <?php }
                                ?>
                            </table>
                        </form>
                        <?php echo $videos->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section("script")
<script type="text/javascript">
    $("#chk-check-all").on('click', function () {
        if ($(this).is(':checked')) {
            $('.chk-ids').each(function () {
                $(this).prop("checked", true);
            });
        } else {
            $('.chk-ids').each(function () {
                $(this).prop("checked", false);
            });
        }
    });

    $("#delete-all").on('click', function(){
        $("#frm-table").submit();
    });
</script>
@endsection