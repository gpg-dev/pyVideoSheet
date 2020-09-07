@extends('frontend.layouts.main')
@section('content')
<div class="content">
    <div class="container">
        <?php if(isset($slug)){ ?>
            <div class="title-main"><?php echo $slug; ?></div>
        <?php } ?>
        <div class="search-category">
            <div class="box-search">
                <label>Sort by:</label>
                <div class="short-list">
                    <a href="<?php echo $url_order . '&order=numOfClicks'; ?>"
                       <?php echo (isset($data_search['order']) && $data_search['order'] == 'numOfClicks')? 'class="active"': ''; ?>>
                        Popularity
                    </a>
                    <a href="<?php echo $url_order . '&order=duration'; ?>"
                       <?php echo (isset($data_search['order']) && $data_search['order'] == 'duration')? 'class="active"': ''; ?>>
                        Duration
                    </a>
                </div>
            </div>
            <div class="box-search">
                <label>Filter by:</label>
                <div class="filter-list">
                    <select class="form-control ddl_filter" name="filter_category">
                        <option value=''>Chose Category</option>
                        <?php foreach($all_categories as $cate){ ?>
                            <?php if(isset($slug) && $slug == $cate->slug){ ?>
                            <option value="<?php echo $cate->slug; ?>" selected="selected"><?php echo $cate->title; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $cate->slug; ?>"><?php echo $cate->title; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <select class="form-control ddl_filter" name="filter_source">
                        <option value=''>Chose Source</option>
                        <?php foreach($all_source as $source){ ?>
                            <?php if(isset($data_search['source_id']) && $data_search['source_id'] == $source->id){ ?>
                            <option value="<?php echo $source->id; ?>" selected="selected"><?php echo $source->title; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $source->id; ?>"><?php echo $source->title; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <?php if(isset($videos) && count($videos) > 0){ ?>
            <?php
            $count_video = 0;
            $first_ad = rand(0,3);
            $element = 0;
            ?>
        <?php foreach($videos as $video){ ?>
            <?php
            if($count_video % 4 == 0){ echo '<div class="row">'; }
            if($count_video == $first_ad && isset($ad_video[$element])){ ?>
            <div class="col-sm-4 col-lg-3" >
                <div class="box-porn">
                    <div class="dv_adv_control">
                        @if(strpos($ad_video[$element]->content, 'http') !== false && strpos($ad_video[$element]->content, 'http') == 0)
                            <img src="{{$ad_video[$element]->content}}" alt="{{$ad_video[$element]->name}}" style="width: 100%;"/>
                        @else
                            {{$ad_video[$element]->content}}
                        @endif
                    </div>
                </div>
            </div>
            <?php
            switch ($element){
                case 0:
                    if($count_video == 3){
                        $first_ad = rand(5,7);
                    }else{
                        $first_ad = rand(4,7);
                    }
                    break;
                case 1:
                    if($count_video == 7){
                        $first_ad = rand(9,11);
                    }else{
                        $first_ad = rand(8,11);
                    }
                    break;
            }
            $element++;
            if($count_video % 4 == 3){ echo '</div><div class="row">'; }
                $count_video++;
            ?>
            <?php } ?>
            <div class="col-sm-4 col-lg-3">
                <div class="box-porn">
                    <a href="<?php echo URL::to('/video/' . $video->id); ?>" target="_blank">
                        <div class="img-porn">
                            <img src="{{strpos($video->image, 'http') === false && strpos($video->image, 'https') === false ? url('uploads/images/'.$video->image) : $video->image}}">
                        </div>
                        <div class="text-porn">
                            <h5>
                                <?php echo $video->title; ?>
                            </h5>
                            <div class="bottom-product">
                                <div class="sourch-product"></div>
                                <div class="view-product"><i class="fa fa-eye"></i><?php echo $video->numOfClicks; ?></div>
                                <div class="date-product"><i class="fa fa-calendar"></i><?php echo date("H:i:s", $video->duration); ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php
            if($count_video % 4 == 3 || $count_video == (count($videos) + count($ad_video) - 1)){ echo '</div>'; }
            $count_video++; ?>
        <?php } ?>
        <?php if($element < count($ad_video)){ ?>
            <?php for($element; $element < count($ad_video); $element++){ ?>
            <?php if($count_video % 4 == 0){ echo '<div class="row">'; } ?>
            <div class="col-sm-4 col-lg-3" >
                <div class="box-porn">
                    @if(strpos($ad_video[$element]->content, 'http') !== FALSE || strpos($ad_video[$element]->content, 'base64') !== FALSE)
                        <div class="dv_adv_control">
                            <img src="{{$ad_video[$element]->content}}" alt="{{$ad_video[$element]->name}}" style="width: 100%;"/>
                        </div>
                    @endif
                </div>
            </div>
            <?php
                if($count_video % 4 == 3 || $count_video == (count($videos) + count($ad_video) - 1)){
                    echo '</div>';
                }
             ?>
            <?php $count_video++;
                } ?>
        <?php } ?>

        <?php } else { ?>
            <h4 style="text-align: center; padding: 10% 0px;">No item was found.</h4>
        <?php } ?>
        <nav aria-label="Page navigation" id="pagination">
            <?php echo $video_paging->render(); ?>
        </nav>

        <div class="title-main">All Porn <span>Categories</span></div>

        <?php
            if(count($all_categories)>0){
                $lastChar = "";
                $i = 1;

                $categories_number = $all_categories->filter(function($category){
                    return is_numeric($category->title[0]);
                });

                if(count($categories_number) > 0){
                    echo '<div class="row category-main">';
                    echo '<div class="col-xs-6 col-sm-4 col-lg-3">' .
                                    '<div class="title-main"><span>No.</span></div>' .
                                    '<ul class="list-category">';
                    $number_item = 0;
                    foreach($categories_number as $cate){
                        echo '<li><a href="' . URL::to('/filter-videos/' . $cate->slug) . '">' . $cate->title . '</a></li>';
                        $number_item++;
                        if($number_item > 9){
                            break;
                        }
                    }
                    echo '</ul></div>';
                    $i++;
                }

                foreach(range('a', 'z') as $word){
                    $cateogories = $all_categories->filter(function($category) use ($word){
                        return strtolower($category->title[0]) == $word;
                    });

                    if(count($cateogories) > 0){
                        if($i == 1){
                            echo '<div class="row category-main">';
                        } else if($i%4==1){
                            echo '</div><div class="row category-main">';
                        }

                        echo '<div class="col-xs-6 col-sm-4 col-lg-3">' .
                                '<div class="title-main"><span>' . $word . '</span></div>' .
                                '<ul class="list-category">';
                        $number_item = 0;
                        foreach($cateogories as $cate){
                            echo '<li><a href="' . URL::to('/filter-videos/' . $cate->slug) . '">' . $cate->title . '</a></li>';
                            $number_item++;
                            if($number_item > 9){
                                break;
                            }
                        }
                        echo '</ul></div>';
                        $i++;
                    }
                }

                echo '</div>';
            }
            ?>
        <div class="row" style="clear: both"></div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $('.ddl_filter').on('change', function() {
            var url = "<?php echo URL::to('/filter-videos'); ?>";
            if($('select[name=filter_category]').val() !== ""){
                url += "/" + $('select[name=filter_category]').val() + "";
            }

            if($('select[name=filter_source]').val() !== ""){
                url += "?source_id=" + $('select[name=filter_source]').val();
            }

            location.href = url;
        });
    </script>
@endsection