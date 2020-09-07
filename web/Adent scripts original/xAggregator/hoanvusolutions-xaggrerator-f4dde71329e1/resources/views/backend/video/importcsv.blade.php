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
        <div id="dv-upload-file">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Import Videos By File CSV</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body list-item">
                            {{Form::open(array('id' => 'login-form', 'class' => 'form-vertical', 'enctype' => 'multipart/form-data')) }}
                            <div class="row">
                                <div class="col col-md-4">
                                    <select name="sourcesite" id='sourcesite' class="form-control" required="">
                                        <option value="" >Select Source Site</option>
                                        @foreach( $sourceSites as $item )
                                        <option value="{{$item->id}}" data-format="{{$item->formatCSVFrom}}">
                                            {{$item->title}}
                                            ({{
                                                ($item->formatCSVFrom == 'pornhub' || $item->formatCSVFrom == 'redtube' ||
                                                $item->formatCSVFrom == 'spankwire' || $item->formatCSVFrom == 'xtube') ? 'Hubtraffic.com' : $item->formatCSVFrom
                                            }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-3 dv-file"><input type="file" name="file" accept=".csv"/></div>
                                <div class="col col-md-2">
                                    <a id="btn_upload_file" class="btn btn-primary">Upload</a>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="dv-process-upload-file">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Process Import CSV</h3>
                            <a class="btn btn-warning pull-right" id="btn-continuous-import" style="display: none;"
                               href="<?php echo action('Backend\VideoController@getImportCSV');?>">
                                Import Another File
                            </a>
                        </div><!-- /.box-header -->
                        <div class="box-body list-item">
                            <div class="row">
                                <div class="col col-md-12">
                                    <h4>Finished number line <span id="number_finished"></span></h4>
                                </div>
                                <div class="col col-md-12">
                                    <div id="myProgress">
                                        <div id="myBar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="dv-video-detail">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body list-item">
                        <div class="row" style="margin-bottom: 10px;">
                            <div id="dv-read-file" class="col-sm-12">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <h4 style="width: fit-content; display: inline-block; margin-right: 20px;">Delimiter </h4>
                                <input type="text" maxlength="1" class="form-control" name="txt_delimiter" value="|"
                                       style="display: inline-block; width: 31px;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="portlet light">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold">One Item In .CSV</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <!--BEGIN TABS-->
                                <div class="tab-content">
                                    <div class="slimScrollDiv">
                                        <div class="scroller" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2" data-initialized="1">
                                            <ul class="feeds" id="dv-info-item">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="portlet light">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold">Form insert Video</span>
                                </div>
                                <div class="actions">
                                    <a id="btn_import_csv" class="btn btn-transparent red-sunglo ">Import CSV </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <!--BEGIN TABS-->
                                <div class="tab-content">
                                    <div class="slimScrollDiv">
                                        <div class="scroller" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2" data-initialized="1">
                                            <form enctype="multipart/form-data" class="form-horizontal" id="frm-product-detail">
                                                <ul class="feeds" id="dv-info-item">
                                                    <li class="form-group">
                                                        <label class="col-md-3 control-label">Video Id</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="videoId" class="form-control" placeholder="key in 1 to select column 1">
                                                        </div>
                                                    </li>
                                                    <li class="form-group">
                                                        <label class="col-md-3 control-label">Url</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="url" class="form-control" placeholder="key in 1 to select column 1">
                                                        </div>
                                                    </li>
                                                    <li class="form-group">
                                                        <label class="col-md-3 control-label">Title</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="title" class="form-control" placeholder="key in 1 to select column 1">
                                                        </div>
                                                    </li>
                                                    <li class="form-group">
                                                        <label class="col-md-3 control-label">Description</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="description" class="form-control" placeholder="key in 1 to select column 1">
                                                        </div>
                                                    </li>
                                                    <li class="form-group">
                                                        <label class="col-md-3 control-label">Image</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="image" class="form-control" placeholder="key in 1 to select column 1">
                                                        </div>
                                                    </li>
                                                    <li class="form-group">
                                                        <label class="col-md-3 control-label">Duration</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="duration" class="form-control" placeholder="key in 1 to select column 1">
                                                        </div>
                                                    </li>
                                                    <li class="form-group">
                                                        <label class="col-md-3 control-label">Categories</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="categories" class="form-control" placeholder="key in 1 to select column 1">
                                                        </div>
                                                    </li>
                                                    <li class="form-group">
                                                        <div class="col-md-3"></div>
                                                        <label class="col-md-3 control-label">Delimiter </label>
                                                        <div class="col-md-2">
                                                            <input type="text" name="delimiter_cate" maxlength="1" class="form-control" placeholder=";">
                                                        </div>
                                                    </li>
                                                    <li class="form-group">
                                                        <label class="col-md-3 control-label">Tags</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="tags" class="form-control" placeholder="key in 1 to select column 1">
                                                        </div>
                                                    </li>
                                                    <li class="form-group">
                                                        <div class="col-md-3"></div>
                                                        <label class="col-md-3 control-label">Delimiter </label>
                                                        <div class="col-md-2">
                                                            <input type="text" name="delimiter_tag" maxlength="1" class="form-control" placeholder=";">
                                                        </div>
                                                    </li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section("script")
<script type="text/javascript">
    var one_line_in_csv = '';
    var total_line_csv = 0;
    function generateLine(strLine) {
        var char = $("input[name=txt_delimiter]").val();
        var arr_line = strLine.split(char);
        var html = '<li><div class="row">' +
                '<div class="col-sm-2"><h4>column</h4></div>' +
                '<div class="col-sm-10"><h4>content</h4></div>' +
                '</div></li>';
        for (var i = 0; i < arr_line.length; i++) {
            if (arr_line[i].indexOf('iframe') > -1) {
                html += '<li><div class="row">' +
                        '<div class="col-sm-2"><h5>' + (i + 1) + '</h5></div>' +
                        '<div class="col-sm-10"><pre>' + $('<div/>').text(arr_line[i]).html() + '</pre></div>' +
                        '</div></li>';
            } else {
                html += '<li><div class="row">' +
                        '<div class="col-sm-2"><h5>' + (i + 1) + '</h5></div>' +
                        '<div class="col-sm-10"><h5>' + arr_line[i] + '</h5></div>' +
                        '</div></li>';
            }
        }
        $("#dv-info-item").html(html);
    }

    $("input[name=txt_delimiter]").on('blur', function () {
        generateLine(one_line_in_csv);
    });

    $("#btn_upload_file").on('click', function () {
        var file = $('input[name=file]')[0].files[0];
        if (!file) {
            $("#dv-upload-file").prepend('<div class="alert alert-danger alert-dismissible fade in col-md-12" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>X</span></button>' +
                    '<p>Please select file .csv</p>' +
                    '</div>');
            return;
        }

        $("#loadingDiv").show();

        var navigator = new FileNavigator(file);
        var indexToStartWith = 0;
        var countLines = 0;

        navigator.readSomeLines(indexToStartWith, function linesReadHandler(err, index, lines, eof, progress) {
            if (err) {
                $("#dv-upload-file").prepend('<div class="alert alert-danger alert-dismissible fade in col-md-12" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>X</span></button>' +
                        '<p>Error: ' + err + '</p>' +
                        '</div>');
                return;
            }

            if (index === 0) {
                one_line_in_csv = lines[1];
                generateLine(lines[1]);
            }

            countLines += lines.length;

            if (eof) {
                $("#dv-read-file").html('');
                $("#dv-read-file").append("<h3 class='box-title'>Read the file <b>" + file.name + "</b></h3>");
                $("#dv-read-file").append('Total ' + countLines + ' record readed');
                $("#dv-video-detail").show();
                $("#loadingDiv").hide();
                total_line_csv = countLines;
                return;
            }

            navigator.readSomeLines(index + lines.length, linesReadHandler);
        });
    });

    $("#btn_import_csv").on('click', function () {
        if (typeof $("select[name=sourcesite]").val() === "undefined" || $("select[name=sourcesite]").val() === '') {
            $("#dv-upload-file").prepend('<div class="alert alert-danger alert-dismissible fade in col-md-12" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>X</span></button>' +
                    '<p>Error: Please choose one source site.</p>' +
                    '</div>');
            return;
        } else {
            var file = $('input[name=file]')[0].files[0];
            if (!file) {
                $("#dv-upload-file").prepend('<div class="alert alert-danger alert-dismissible fade in col-md-12" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>X</span></button>' +
                        '<p>Please select file .csv</p>' +
                        '</div>');
                return;
            }

            $("#loadingDiv").show();
            $("#dv-upload-file").hide();
            $("#dv-process-upload-file").show();
            window.location.hash = '#dv-process-upload-file';
            readFileAndPost(1, 50, file);
        }
    });

    function getAllInfoProduct(data) {
        data.append("sourcesite", $("select[name=sourcesite]").val());
        data.append("txt_delimiter", $("input[name=txt_delimiter]").val());
        data.append("videoId", $("input[name=videoId]").val());
        data.append("url", $("input[name=url]").val());
        data.append("title", $("input[name=title]").val());
        data.append("description", $("input[name=description]").val());
        data.append("image", $("input[name=image]").val());
        data.append("duration", $("input[name=duration]").val());
        data.append("categories", $("input[name=categories]").val());
        data.append("delimiter_cate", $("input[name=delimiter_cate]").val());
        data.append("tags", $("input[name=tags]").val());
        data.append("delimiter_tag", $("input[name=delimiter_tag]").val());
        data.append("_token", $("input[name=_token]").val());

        return data;
    }

    function readFileAndPost(indexStart, indexTake, file) {
        if (indexStart >= total_line_csv) {
            $("#dv-process-upload-file").prepend('<div class="alert alert-success alert-dismissible fade in col-md-12" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>X</span></button>' +
                    '<p>SUCCESS: upload file success.</p>' +
                    '</div>');
            $("#loadingDiv").hide();
            window.location.hash = '#dv-process-upload-file';
            $("#btn-continuous-import").show();
            return;
        } else if (indexStart + indexTake > total_line_csv) {
            indexTake = total_line_csv - indexStart;
        }

        var navigator = new FileNavigator(file);

        navigator.readLines(indexStart, indexTake, function (err, index, lines, eof, progress) {
            if (err) {
                console.log('Error: ' + err);
                return;
            }

            var formData = new FormData();
            formData.append("all_items", JSON.stringify(lines));
            formData = getAllInfoProduct(formData);
            $.ajax({
                url: '<?php echo action('Backend\VideoController@postItemsCSV'); ?>',
                type: 'POST',
                data: formData,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function (data, textStatus, jqXHR)
                {
                    if (typeof data.error === 'undefined')
                    {
                        if (data.isSuccess) {
                            var width = (indexStart + indexTake) * 100 / (total_line_csv);
                            $("#myBar").attr('style', 'width:' + width + '%;');
                            $("#number_finished").html((indexStart + indexTake) + " / " + total_line_csv);
                            readFileAndPost(indexStart + indexTake, indexTake, file);
                        }
                    } else {
                        $("#dv-upload-file").show();
                        $("#dv-process-upload-file").hide();
                        $("#loadingDiv").hide();
                        $("#dv-upload-file").prepend('<div class="alert alert-danger alert-dismissible fade in col-md-12" role="alert">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>X</span></button>' +
                                '<p>Import CSV error. Please check field was inputed.</p>' +
                                '</div>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $("#dv-upload-file").show();
                    $("#dv-process-upload-file").hide();
                    $("#loadingDiv").hide();
                    $("#dv-upload-file").prepend('<div class="alert alert-danger alert-dismissible fade in col-md-12" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>X</span></button>' +
                            '<p>Import CSV error. Please check field was inputed.</p>' +
                            '</div>');
                }
            });
        });
    }
</script>
@endsection