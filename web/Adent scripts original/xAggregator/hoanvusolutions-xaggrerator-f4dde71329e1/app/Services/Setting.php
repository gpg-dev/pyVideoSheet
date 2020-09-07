<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

/**
 * Description of Setting
 *
 * @author pc
 */
class Setting {
    protected static $settings;
    protected static $getKey;
    protected static $adsFooter;
    protected static $keyAds;
    protected static $staticPages;
    protected static $categoryOtherId;
    protected static $sourceSite;

    protected function __construct()
    {
        
    }
    
    public static function getLogo($key){
        if (empty(self::$settings)) {
            self::$settings = \App\Models\Setting::where('isDeleted', '=', 0)->orWhereNull('isDeleted')->get();
        }
        self::$getKey = $key;
        $set = self::$settings->filter(function($value,$k){
            return $value->key == self::$getKey;
        });
        
        if($set->first()){
            $value = $set->first()->content;
            if(strpos($value, 'http') !== false){
                return $value;
            }          
            return \Illuminate\Support\Facades\URL::to(UploadHandler::UPLOAD_URL.'images/'.$value); 
        }else{
            return '';
        }
    }
    
    public static function getValue($key){
        if (empty(self::$settings)) {
            self::$settings = \App\Models\Setting::where('isDeleted', '=', 0)->orWhereNull('isDeleted')->get();
        }
        self::$getKey = $key;
        $set = self::$settings->filter(function($value,$k){
            return $value->key == self::$getKey;
        });
        if($set->first()){
            return $set->first()->content;
        }else{
            return '';
        }
    }
    
    public static function getMetas(){
        if (empty(self::$settings)) {
            self::$settings = \App\Models\Setting::where('isDeleted', '=', 0)->orWhereNull('isDeleted')->get();
        }
        $set = self::$settings->filter(function($value,$k){
            return $value->type == 'meta';
        });
        if($set){
            return $set;
        }else{
            return '';
        }
    }
    
    public static function getAds($key){
        if (empty(self::$adsFooter)) {
            self::$adsFooter = \App\Models\Advertise::where('isActive', '1')->where('isDeleted', '0')
                    ->inRandomOrder()->get();
        }
        self::$keyAds = $key;
        $ad = self::$adsFooter->filter(function($value,$k){
            return $value->type == self::$keyAds;
        });
        
        if($ad){
            $number = 3;
            if(count($ad) < 3){
                $number = count($ad);
            }
            return $ad->random($number);
        }else{
            return '';
        }
    }
    
    public static function getStaticPages(){
        if (empty(self::$staticPages)) {
            self::$staticPages = \App\Models\Page::where('isActive', '1')->where('isDeleted', '0')->get();
        }
        
        return self::$staticPages;
    }
    
    public static function getOtherCateId(){
        if (empty(self::$categoryOtherId)) {
            $category = \App\Models\Category::where('title', 'Other')->firstOrFail();
            if($category){
                self::$categoryOtherId = $category->id;
            }
        }
        
        return self::$categoryOtherId;
    }
    
    public static function getSourceSites(){
        if (empty(self::$sourceSite)) {
            $sourceSite = \App\Models\SourceSite::select(array('id', 'title'))->get()->toArray();
            if($sourceSite){
                self::$sourceSite = $sourceSite;
            }
        }
        
        return self::$sourceSite;
    }
}
