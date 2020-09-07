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
            <h3 class="box-title">Import Videos</h3>
          </div><!-- /.box-header -->
          
          <div class="box-body list-item"> 
            <div class="note note-warning">
                <h4 class="block">Info! About SourceSite</h4>
                <h5><b><i>When you get file .csv, Please choose "field delimiter" is "|" </i></b></h5>
                
                <ul>
                    <li>The tubecorporate.com can import video by <b>link</b>. <br/>
                        <i>Include: Txxx.com, HClips.com, Upornia.com, HdZog.com, HotMovs.com, VoyeurHit.com, 
                        Vjav.com, TubePornClassic.com, TheGay.com</i>
                    </li>
                    <li>The xvideos.com can import video by <b>link</b>.</li>
                    <li>The xhamster.com can import video by <b>link</b> ( Choose all field in step 4 When you get file .csv ).</li>
                    <li>The sites Spankwire import video by <b>link</b>. ( ONLY Format <b>JSON</b> ).</li>
                </ul>
            </div>
            {{Form::open(array('url' => action('Backend\VideoController@postImport'), 'id' => 'login-form', 'class' => 'form-vertical', 'enctype' => 'multipart/form-data', 'method' => 'POST')) }}
            <div class="row">
                <div class="col col-md-4">
                    <select name="sourcesite" id='sourcesite' class="form-control" required="">
                        <option value="" >Select Source Site</option>
                        @foreach( $sourceSites as $item )
                        <option value="{{$item->id}}" data-format="{{$item->formatCSVFrom}}">{{$item->title}} ({{$item->formatCSVFrom}})</option>
                        @endforeach                    
                    </select>
                </div>
                <div class="col col-md-3 dv-url-source">
                    <input class="form-control" type="text" name="url_source" placeholder="link of source site to import"/>
                </div>
                <div class="col col-md-2">
                    <input type="submit" value="Import" class="btn btn-primary"/>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
              </div>
            {{Form::close()}}
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@section("script")
<script type="text/javascript">
    $(document).on("click", "#sourcesite", function(){
        var format = $("#sourcesite option:selected").attr('data-format');
        if(format === "spankwire"){
            $(".dv-url-source").show();
            $(".dv-file").hide();
        } else if(format === "pornsharing.com" || format === "pornhub"
                || format === "redtube" || format === "xtube"){
            $(".dv-url-source").hide();
            $(".dv-file").show();
        } else if(format === "xvideos.com" || format === "tubecorporate.com" || format === "xhamster.com"){
            $(".dv-url-source").show();
            $(".dv-file").show();
        } else {
            $(".dv-url-source").show();
            $(".dv-file").show();
        }
    })
</script>
@endsection