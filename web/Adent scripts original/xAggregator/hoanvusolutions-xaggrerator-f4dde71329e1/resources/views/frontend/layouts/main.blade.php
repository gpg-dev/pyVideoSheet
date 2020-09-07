<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <?php foreach (\App\Services\Setting::getMetas() as $meta){ ?>
    <meta name="<?php echo $meta->key; ?>" content="<?php echo $meta->content; ?>">
    <?php } ?>
    <title><?php echo \App\Services\Setting::getValue('title_page'); ?></title>
    <link rel="icon" href="<?php echo \App\Services\Setting::getLogo('favicon'); ?>" type="image/x-icon" />
    <link href="{{URL::asset('/frontends/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/frontends/font-awesome/css/font-awesome.css')}}" rel="stylesheet">      
    <link href="{{URL::asset('/frontends/css/style.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/frontends/css/responsive.css')}}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script type='text/javascript' src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script type='text/javascript' src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
    <![endif]-->
    <?php echo \App\Services\Setting::getValue('google_analytics'); ?>
  </head>
  <body>    

    <div class="header">
      <div class="container">
        <a href="#" class="toggle-menu"><i class="fa fa-bars"></i> </a>
        <div class="logo"><a href="<?php echo URL::to('/'); ?>">
                <img src="<?php echo \App\Services\Setting::getLogo('header_logo'); ?>"></a></div>
        <div class="right-header">
          <div class="search-top">
            <form action="<?php echo URL::to('/videos'); ?>" method="GET">
              <input type="text" name="search" class="form-control" placeholder="Search videos"
                     value="<?php echo isset($data_search['search'])?$data_search['search']:''; ?>">
              <button class="btn btn-warning"><i class="fa fa-search"></i></button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="menu">
      <div class="bg-menu-mobile"></div>
      <div class="container">
        <ul>
          <li class="category"><a href="#"><i class="fa fa-bars"></i> Top Categories</a>
              <?php $categories = App\Models\Category::whereRaw('isDeleted IS NULL OR isDeleted = 0')
                      ->orderBy('numOfClicks', 'DESC')->limit(30)->get(); ?>
              <ul class="list_category_top">
                <?php foreach($categories as $cate){ ?>
                <li><a href="<?php echo URL::to('/filter-videos/' . $cate->slug); ?>"><?php echo $cate->title; ?></a></li>
                <?php } ?>
              </ul>
          </li>
          <li><a href="<?php echo URL::to('/filter-videos?order=created_at&sort=desc'); ?>">New Videos</a></li>
          <li><a href="<?php echo URL::to('/filter-videos?order=numOfClicks'); ?>">Popular Videos</a></li>
          <!--<li><a href="#">HD Porn</a></li>-->
        </ul>
      </div>
    </div>

    @yield('content')
    <div class="advertise-container">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h4 style="text-align: center; color:#ff5a00">ADVERTISEMENT</h4>
                </div>
            </div>
            <div class="row">
                <?php $ad_footers = \App\Services\Setting::getAds('adsFooter'); ?>
                <?php foreach ($ad_footers as $ad_footer){ ?>
                <div class="col-sm-4">
                    <div class="dv_adv_control">
                        <?php if(strpos($ad_footer->content, 'http') !== false && strpos($ad_footer->content, 'http') == 0){ ?>
                        <img src="<?php echo $ad_footer->content; ?>" alt="<?php echo $ad_footer->name; ?>" style="width: 100%;"/>
                        <?php }else{ ?>
                        <?php echo $ad_footer->content; ?>
                        <?php } ?>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    <div class='static-page-container'>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <h4 class="title-static-page">Informations</h4>
                    <?php $staticPages = \App\Services\Setting::getStaticPages(); ?>
                    <ul>
                        <?php foreach($staticPages as $page){ ?>
                            <li><a href="<?php echo URL::to('/page') . '/' . $page->slug; ?>"><?php echo $page->title; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-sm-4">
                    <h4 class="title-static-page">Help and Support</h4>
                    <ul>
                        <li><a href="<?php echo URL::to('/contact'); ?>">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-sm-4">
                    <div class="right-footer">
                        <div class="logo-footer">
                          <img src="<?php echo \App\Services\Setting::getLogo('footer_logo'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">    
        <div class="container">
          <div class="left-footer">
            All models were 18 years of age or older at the time of depiction. <br/>
            <?php echo \App\Services\Setting::getValue('footer_text'); ?>
          </div>
        </div>
    </div>
    <script src="{{URL::asset('/frontends/js/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('/frontends/js/bootstrap.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-menu').click(function() {
                $('.menu').show();
                $('.bg-menu-mobile').show();
            });
            $('.bg-menu-mobile').click(function() {
                $('.menu').hide();
                $(this).hide();
            });
        });
    </script>
    <?php echo \App\Services\Setting::getValue('before_body'); ?>
    @yield('script')      

  </body>
</html>
