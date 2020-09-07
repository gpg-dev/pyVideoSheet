<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Advertise;
use App\Services\DefineConfig;
use App\Services\Setting;

/**
 * Description of SiteController
 *
 * @author hoangtuan438
 */
class SiteController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
    protected function template($view){
        return 'frontend.site.'.$view;
    }

    public function index(){
        $paging = 32;
        $ad_cate = array();
        $ad_categories = Setting::getAds('adsCate');
        if($ad_categories){
            $paging = $paging - count($ad_categories);
            foreach ($ad_categories as $cate){
                $ad_cate[] = $cate;
            }
        }
        $categories = Category::where('isActive', '1')->where('isDeleted', '=', 0)
                ->orWhereNull('isDeleted')
                ->orderBy('numOfClicks', 'DESC')
                ->paginate($paging);
        
        $all_categories = Category::where('isActive', '1')->where('isDeleted', '=', 0)
                ->orWhereNull('isDeleted')->orderBy('title', 'ASC')->get();
        
        $tags = Tag::where('isDeleted', NULL)->orderBy('title', 'ASC')->take(100)->get();

        return View($this->template('index'))
                ->with('categories', $categories)
                ->with('all_categories', $all_categories)
                ->with('tags', $tags)
                ->with('ad_cate', $ad_cate);
    }
  
    public function notFound() {
        return View($this->template('not-found'));
    }

    public function notPermission() {
        return View($this->template('not-permission'));
    }
}
