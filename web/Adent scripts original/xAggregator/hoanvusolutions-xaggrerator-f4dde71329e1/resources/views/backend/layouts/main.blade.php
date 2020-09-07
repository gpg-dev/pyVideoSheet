<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">   
    
    <title>XAgrerator</title>		    
    
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">        
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{URL::asset('backends/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{URL::asset('backends/dist_custom/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{URL::asset('backends/dist_custom/css/skins/_all-skins.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{URL::asset('backends/plugins/iCheck/flat/blue.css')}}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{URL::asset('backends/plugins/morris/morris.css')}}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{URL::asset('backends/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{URL::asset('backends/plugins/datepicker/datepicker3.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{URL::asset('backends/plugins/daterangepicker/daterangepicker-bs3.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{URL::asset('backends/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">  
    <link href="{{URL::asset('/backends/css/layout.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/backends/css/components.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{URL::asset('backends/plugins/select2/select2.min.css')}}">  
    <link rel="stylesheet" href="{{URL::asset('backends/tag-it/jquery.tagit.css')}}">  
    <link rel="stylesheet" href="{{URL::asset('backends/tag-it/tagit.ui-zendesk.css')}}">  
    @yield('css')
    <link rel="stylesheet" href="{{URL::asset('backends/css/main.css')}}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->    
  </head>
  <body class="skin-blue">
    <div id="loadingDiv">
        <img src="{{URL::asset('backends/images/hex-loader2.gif')}}" alt="loading"/>
    </div>
    <div class="wrapper">
      <?php       
      $controller = class_basename(Route::currentRouteAction());
      list($controller, $actionController) = explode('@', $controller);            
      ?>
      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo action('Backend\SiteController@index')?>" class="logo">          
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">xAggrerator</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->                            
              <li>
                  <a href='<?php echo action('Backend\SiteController@logout'); ?>'><i class="fa fa-power-off"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      
       <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            
          </div>          
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?php if($controller === 'VideoController')echo 'active';?> treeview">              
              <a href="#">
                <i class="fa fa-list"></i> <span>Videos</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($controller === 'VideoController' && $actionController === 'index')echo 'active';?>">
                  <a href="<?php echo action('Backend\VideoController@index')?>"><i class="fa fa-minus"></i> List</a>
                </li>
                <li class="<?php if($controller === 'VideoController' && $actionController === 'getCreate')echo 'active';?>">
                  <a href="<?php echo action('Backend\VideoController@getCreate')?>"><i class="fa fa-minus"></i>Create New</a>
                </li>
                <li class="<?php if($controller === 'VideoController' && $actionController === 'getImport')echo 'active';?>">
                  <a href="<?php echo action('Backend\VideoController@getImport')?>"><i class="fa fa-minus"></i>Import Link</a>
                </li>
                <li class="<?php if($controller === 'VideoController' && $actionController === 'getImportCSV')echo 'active';?>">
                  <a href="<?php echo action('Backend\VideoController@getImportCSV')?>"><i class="fa fa-minus"></i>Import File CSV</a>
                </li>
              </ul>
            </li>
            <li class="<?php if($controller === 'CategoryController')echo 'active';?> treeview">              
              <a href="#">
                <i class="fa fa-adn"></i> <span>Category</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($controller === 'CategoryController' && $actionController === 'index')echo 'active';?>">
                  <a href="<?php echo action('Backend\CategoryController@index')?>"><i class="fa fa-minus"></i> List</a>
                </li>                
                <li class="<?php if($controller === 'CategoryController' && $actionController === 'getCreate')echo 'active';?>">
                  <a href="<?php echo action('Backend\CategoryController@getCreate')?>"><i class="fa fa-minus"></i>Create New</a>
                </li>                
              </ul>
            </li>  
            <li class="<?php if($controller === 'TagController')echo 'active';?> treeview">              
              <a href="#">
                <i class="fa fa-tag"></i> <span>Tag</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($controller === 'TagController' && $actionController === 'index')echo 'active';?>">
                  <a href="<?php echo action('Backend\TagController@index')?>"><i class="fa fa-minus"></i> List</a>
                </li>                
                <li class="<?php if($controller === 'TagController' && $actionController === 'getCreate')echo 'active';?>">
                  <a href="<?php echo action('Backend\TagController@getCreate')?>"><i class="fa fa-minus"></i>Create New</a>
                </li>                
              </ul>
            </li>  
            <li class="<?php if($controller === 'SourceSiteController')echo 'active';?> treeview">              
              <a href="#">
                <i class="fa fa-sitemap"></i> <span>Source Site</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($controller === 'SourceSiteController' && $actionController === 'index')echo 'active';?>">
                  <a href="<?php echo action('Backend\SourceSiteController@index')?>"><i class="fa fa-minus"></i> List</a>
                </li>                
                <!--<li class="<?php // if($controller === 'SourceSiteController' && $actionController === 'getCreate')echo 'active';?>">
                  <a href="<?php // echo action('Backend\SourceSiteController@getCreate')?>"><i class="fa fa-minus"></i>Create New</a>
                </li>-->
              </ul>
            </li>
            <li class="<?php if($controller === 'PageController') echo 'active';?> treeview">              
              <a href="#">
                <i class="fa fa-file"></i><span>Page</span><i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($controller === 'PageController' && $actionController === 'index')echo 'active';?>">
                  <a href="<?php echo action('Backend\PageController@index'); ?>"><i class="fa fa-minus"></i> List</a>
                </li>                
                <li class="<?php if($controller === 'PageController' && $actionController === 'getCreate')echo 'active';?>">
                  <a href="<?php echo action('Backend\PageController@getCreate'); ?>"><i class="fa fa-minus"></i>Create New</a>
                </li>                
              </ul>
            </li>
            <li class="<?php if($controller === 'AdvertiseController') echo 'active';?> treeview">              
              <a href="#">
                <i class="fa fa-image"></i><span>Advertise</span><i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($controller === 'AdvertiseController' && $actionController === 'index')echo 'active';?>">
                  <a href="<?php echo action('Backend\AdvertiseController@index'); ?>"><i class="fa fa-minus"></i> List</a>
                </li>                
                <li class="<?php if($controller === 'AdvertiseController' && $actionController === 'getCreate')echo 'active';?>">
                  <a href="<?php echo action('Backend\AdvertiseController@getCreate'); ?>"><i class="fa fa-minus"></i>Create New</a>
                </li>                
              </ul>
            </li>
            <li class="<?php if($controller === 'SettingController')echo 'active';?> treeview">              
              <a href="#">
                <i class="fa fa-cog"></i> <span>Setting</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($controller === 'SettingController' && $actionController === 'index')echo 'active';?>">
                  <a href="<?php echo action('Backend\SettingController@index')?>"><i class="fa fa-minus"></i>Update</a>
                </li>
              </ul>
            </li>
            <li class="<?php if($controller === 'SiteController' && $actionController === 'getInforUser') echo 'active';?> treeview">              
              <a href="<?php echo action('Backend\SiteController@getInforUser')?>">
                <i class="fa fa-user"></i> <span>Admin Setting</span>
              </a>
            </li>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
      
      
        <!-- jQuery 2.1.4 -->
    <script src="{{URL::asset('backends/plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>    
    
    @yield('content')
    
    </div>

    
  
    <script>
      $.widget.bridge('uibutton', $.ui.button);
     
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{URL::asset('backends/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<!--    <script src="web/plugins/morris/morris.min.js"></script>-->
    <!-- Sparkline -->
    <script src="{{URL::asset('backends/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
    <!-- jvectormap -->
    <script src="{{URL::asset('backends/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script src="{{URL::asset('backends/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{URL::asset('backends/plugins/knob/jquery.knob.js')}}"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="{{URL::asset('backends/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- datepicker -->
    <script src="{{URL::asset('backends/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{URL::asset('backends/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
    <!-- Slimscroll -->
    <script src="{{URL::asset('backends/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{URL::asset('backends/plugins/fastclick/fastclick.min.js')}}"></script>
    <!-- File Navigate -->
    <script src="{{URL::asset('backends/dist_custom/js/file.navigate.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{URL::asset('backends/dist_custom/js/app.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--    <script src="web/dist_custom/js/pages/dashboard.js"></script>-->
    <!-- AdminLTE for demo purposes -->
<!--    <script src="web/dist_custom/js/demo.js"></script>-->
    <!--<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>-->
    <script src="{{URL::asset('backends/plugins/select2/select2.min.js')}}"></script>
    
    <script src="{{URL::asset('backends/tag-it/tag-it.min.js')}}"></script>
    
    @yield('script')     
    <script type="text/javascript">
      $(function () {        
        $('.delete-item').click(function(){
          var r = confirm("Are you sure you want to delete the item?");
          if (r == true) {
            return true;
          }else{
            return false;
          }
        });
//        $(".textarea").wysihtml5();
      
    //    CKEDITOR.replace('editor1');      
      });
    </script>    
     
    
  </body>
</html>