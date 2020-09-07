@extends('frontend.layouts.main')
@section('content')
<div class="content">
  <div class="container">
    <div class="title-main">Popular Porn Categories</div>
        <?php 
            $count_cate = 0;
            $first_ad = rand(0,3);
            $element = 0;
        ?>
      <?php foreach($categories as $item){?>
        <?php if($count_cate % 4 == 0){ echo '<div class="row">'; } ?>
        <?php if($count_cate == $first_ad && isset($ad_cate[$element])){ ?>
            <div class="col-sm-4 col-lg-3">
                <div class="box-porn">
                    <div class="dv_adv_control">
                        <?php if(strpos($ad_cate[$element]->content, 'http') !== false && strpos($ad_cate[$element]->content, 'http') == 0){ ?>
                        <img src="<?php echo $ad_cate[$element]->content; ?>" alt="<?php echo $ad_cate[$element]->name; ?>" style="width: 100%;"/>
                        <?php }else{ ?>
                        <?php echo $ad_cate[$element]->content; ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php 
            switch ($element){
                case 0:
                    if($count_cate == 3){
                        $first_ad = rand(5,7);
                    }else{
                        $first_ad = rand(4,7);
                    }
                    break;
                case 1:
                    if($count_cate == 7){
                        $first_ad = rand(9,11);
                    }else{
                        $first_ad = rand(8,11);
                    }
                    break;
            }
            $element++;
            ?>
        <?php   
                if($count_cate % 4 == 3){ echo '</div><div class="row">'; }
                $count_cate++;
            } ?>
            <div class="col-sm-4 col-lg-3" >
                <div class="box-porn">
                    <div class="img-porn">
                        <a href="<?php echo URL::to('/filter-videos/' . $item->slug); ?>"><img src="<?php echo $item->getImage()?>"></a>
                    </div>
                    <div class="text-porn">
                        <h4><a href="<?php echo URL::to('/filter-videos/' . $item->slug); ?>"><?php echo $item->title;?></a></h4>
                    </div>
                </div>
            </div>
        <?php 
                if($count_cate % 4 == 3 || $count_cate == (count($categories) + count($ad_cate) - 1)){ echo '</div>'; }
                $count_cate++;
            } ?>
    <nav aria-label="Page navigation" id="pagination">
        <?php echo $categories->render(); ?>
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
    <div class="title-main row"><div class="col-sm-12">Popular <span>Search</span></div></div>
    <ul class="list-tag">
        <?php foreach($tags as $tag){ ?>
            <li><a href="<?php echo URL::to('/filter-videos?tag_id=' . $tag->id); ?>"><?php echo $tag->title; ?></a></li>
        <?php } ?>
    </ul>
  </div>
</div>
@endsection