<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\Setting;
use App\Services\DefineConfig;
use App\Models\VideoCategory;
use App\Models\Category;
use App\Models\SourceSite;
use App\Models\VideoTag;

use Illuminate\Support\Facades\URL;
use sngrl\SphinxSearch\SphinxSearch;
use Illuminate\Pagination\Paginator;

use DB;
/**
 * Description of VideoController
 *
 * @author pc
 */
class VideoController extends Controller{
    //put your code here
    protected function template($view){
        return 'frontend.video.'.$view;
    }
    
    public function getVideos(Request $request) {
        if(!$request->input('search') || empty($request->input('search'))){
            return redirect('/filter-videos');
        }
        
        $url_filter = '';
        $paging = 24;
        $ad_video = array();
        $ad_video_result = Setting::getAds('adsVideo');
        if($ad_video_result){
            $paging = $paging - count($ad_video_result);
            foreach ($ad_video_result as $video){
                $ad_video[] = $video;
            }
        }
        
        $videos = Video::query()->where(Video::TABLE . '.isActive', '1');
        
        if($request->input('search')){
            $sphinx = new SphinxSearch();
            $result = $sphinx->search($request->input('search'), 'searchVideo')
                    ->setMatchMode(\Sphinx\SphinxClient::SPH_MATCH_EXTENDED)
                    ->limit(100000)
                    ->setSelect('id')
                    ->query();
            
            if(isset($result['matches']) && count($result['matches']) > 0){
                $videos = $videos->whereIn(Video::TABLE . '.id', array_keys($result['matches']));
            } else {
                $videos = $videos->whereIn(Video::TABLE . '.id', [0]);
            }
            
            if($url_filter){
                $url_filter .= '&search=' . $request->input('search') . '';
            }else{
                $url_filter .= 'search=' . $request->input('search') . '';
            }
        }
        
        if($request->input('order')){
            $videos = $videos->orderBy(Video::TABLE . '.' . $request->input('order'), 'DESC')
                    ->paginate($paging);
        } else {
            $videos = $videos->orderBy(Video::TABLE . '.created_at', 'DESC')
                    ->paginate($paging);
        }
        $all_categories = Category::where('isDeleted', '=', 0)->orWhereNull('isDeleted')->orderBy('title', 'ASC')->get();
        
        $all_source = SourceSite::orderBy('title', 'ASC')->get();
        
        $videos->setPath(URL::to('/videos?' . $url_filter .
                    ($request->input('order') ? '&order=' . $request->input('order') : '')));
        
        return View($this->template('videos'))
                ->with('videos', $videos)
                ->with('all_categories', $all_categories)
                ->with('all_source', $all_source)
                ->with('url_order', URL::to('/videos?' . $url_filter))
                ->with('data_search', $request->input())
                ->with('ad_video', $ad_video);
    }
    
    public function getVideosByCate(Request $request, $slug) {
        $url_filter = '';
        $paging = 24;
        $ad_video = array();
        $ad_video_result = Setting::getAds('adsVideo');
        if($ad_video_result){
            $paging = $paging - count($ad_video_result);
            foreach ($ad_video_result as $video){
                $ad_video[] = $video;
            }
        }
        
        $videos = Video::query()->where(Video::TABLE . '.isActive', '1');
        
        if($slug){
            $category = Category::where('slug', '=', $slug)->firstOrFail();
            $videos = $videos->join(VideoCategory::TABLE . ' AS vc', Video::TABLE . '.id', '=', 'vc.videoId')
                    ->where('vc.catId', $category->id);

            // increase no of click of this category
            if($category){
                $category->numOfClicks++;
                $category->save();
            }
        }

        if($request->input('source_id')){
            $videos = $videos->where(Video::TABLE . '.sourceSiteId', $request->input('source_id'));
            // increase no of click of this source site
            $source_site = SourceSite::find($request->input('source_id'));
            $source_site->numOfClicks++;
            $source_site->save();

            $url_filter .= 'source_id=' . $request->input('source_id') . '';
        }

        if($request->input('tag_id')){
            $videos = $videos->join(VideoTag::TABLE . ' AS vt', Video::TABLE . '.id', '=', 'vt.videoId')
                    ->where('vt.tagId', $request->input('tag_id'));

            if($url_filter){
                $url_filter .= '&tag_id=' . $request->input('tag_id') . '';
            }else{
                $url_filter .= 'tag_id=' . $request->input('tag_id') . '';
            }
        }
        
        
        if($request->input('order')){
            $videos = $videos->orderBy(Video::TABLE . '.' . $request->input('order'), 'DESC')
                    ->paginate($paging);
        } else {
            $videos = $videos->orderBy(Video::TABLE . '.created_at', 'DESC')
                    ->paginate($paging);
        }
        $all_categories = Category::where('isDeleted', '=', 0)->orWhereNull('isDeleted')->orderBy('title', 'ASC')->get();
        
        $all_source = SourceSite::orderBy('title', 'ASC')->get();
        
        $videos->setPath(URL::to('/videos/' . $slug . '?' . $url_filter .
                    ($request->input('order') ? '&order=' . $request->input('order') : '')));
        
        return View($this->template('videos'))
                ->with('videos', $videos)
                ->with('slug', $slug)
                ->with('all_categories', $all_categories)
                ->with('all_source', $all_source)
                ->with('url_order', URL::to('/videos/' . $slug . '?' . $url_filter))
                ->with('data_search', $request->input())
                ->with('ad_video', $ad_video);
    }
    
    public function getAllVideos(Request $request, $slug = null) {
        $url_filter = '';
        $url_slug = '';
        $paging = 24;
        $ad_video = array();
        $ad_video_result = Setting::getAds('adsVideo');
        if($ad_video_result){
            $paging = $paging - count($ad_video_result);
            foreach ($ad_video_result as $video){
                $ad_video[] = $video;
            }
        }
        
        $str_stored = '';
        $str_stored_total = '';
        if($request->input('source_id')){
            // increase no of click of this source site
            $source_site = SourceSite::find($request->input('source_id'));
            $source_site->numOfClicks++;
            $source_site->save();

            $url_filter .= 'source_id=' . $request->input('source_id') . '';
            
            $str_stored .= $request->input('source_id') . ',';
            $str_stored_total .= $request->input('source_id') . ',';
        }else{
            $str_stored .= '0,';
            $str_stored_total .= '0,';
        }
        
        if($slug){
            $category = Category::where('slug', '=', $slug)->firstOrFail();
            // increase no of click of this category
            if($category){
                $category->numOfClicks++;
                $category->save();
            }
            
            $str_stored .= $category->id . ',';
            $str_stored_total .= $category->id . ',';
            $url_slug = '/' . $slug;
        }else{
            $str_stored .= '0,';
            $str_stored_total .= '0,';
        }
        
        if($request->input('tag_id')){
            if($url_filter){
                $url_filter .= '&tag_id=' . $request->input('tag_id') . '';
            }else{
                $url_filter .= 'tag_id=' . $request->input('tag_id') . '';
            }
            
            $str_stored .= $request->input('tag_id') . ',';
            $str_stored_total .= $request->input('tag_id');
        }else{
            $str_stored .= '0,';
            $str_stored_total .= '0';
        }
        
        if($request->input('order')){
            $str_stored .= '\'' . $request->input('order') . '\',';
        } else {
            $str_stored .= '\' \',';
        }
        
        if($request->input('page')){
            $skip = ((int)$request->input('page') - 1) * $paging;
            $str_stored .= $skip . ',' . $paging;
        } else {
            $str_stored .= '0,' . $paging;
        }
        
        $videos = DB::select('call sp_getVideosPagination(' . $str_stored . ')');
        
        $number_total = 0;
        $total = DB::select('call sp_getVideosTotal(' . $str_stored_total . ')');
        if($total){
            $number_total = $total[0]->total;
        }
        $all_categories = Category::where('isDeleted', '=', 0)->orWhereNull('isDeleted')->orderBy('title', 'ASC')->get();
        
        $all_source = SourceSite::orderBy('title', 'ASC')->get();
        
        $video_paging = new \Illuminate\Pagination\LengthAwarePaginator($videos, $number_total, $paging, $request->input('page'), [
            'path' => URL::to('/filter-videos' . $url_slug . '?'  . $url_filter .
                    ($request->input('order') ? '&order=' . $request->input('order') : '')),
        ]);
        
        return View($this->template('filter-videos'))
                ->with('videos', $videos)
                ->with('video_paging', $video_paging)
                ->with('slug', $slug)
                ->with('all_categories', $all_categories)
                ->with('all_source', $all_source)
                ->with('url_order', URL::to('/filter-videos' . $url_slug . '?' . $url_filter))
                ->with('data_search', $request->input())
                ->with('ad_video', $ad_video);
    }
    
    public function showVideo($id) {
        $video = Video::find($id);
        if($video){
            $video->numOfClicks++;
            $video->save();
        }else{
            return redirect('/404');
        }
        
        return redirect($video->url);
    }
}
