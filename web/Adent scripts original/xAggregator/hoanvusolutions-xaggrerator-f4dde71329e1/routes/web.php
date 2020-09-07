<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/



Route::group(['namespace' => 'Frontend'], function () {
  Route::get('/', 'SiteController@index');
  Route::get('/filter-videos/{slug?}', 'VideoController@getAllVideos');
  Route::get('/videos', 'VideoController@getVideos');
  Route::get('/video/{id}', 'VideoController@showVideo');
  Route::get('/page/{slug}', 'PageController@getPages');
  Route::get('/contact', 'PageController@contactUs');
  Route::get('/404', 'SiteController@notFound');
  Route::get('/403', 'SiteController@notPermission');
});

Route::group(['prefix' => 'backend', 'namespace' => 'Backend'], function () {  
  Route::get('/login', 'SiteController@getLogin');
  Route::get('/logout', 'SiteController@logout');
  Route::post('/post-login', 'SiteController@postLogin');
  Route::group(['middleware' => 'authAdmin'], function(){
    Route::get('/', 'SiteController@index');
    
    Route::get('/setting', 'SettingController@index'); 
    Route::post('/setting', 'SettingController@postUpdate');
    
    Route::get('/user', 'SiteController@getInforUser'); 
    Route::post('/user', 'SiteController@postInforUser');
    
    Route::get('/report/category', 'ReportController@category');  
    Route::get('/report/source-site', 'ReportController@source');
    
    Route::get('/videos', 'VideoController@index');
    Route::post('/videos/uploadCSV', 'VideoController@postUploadCSV');
    Route::post('/videos/itemsCSV', 'VideoController@postItemsCSV');
    Route::get('/videos/importCSV', 'VideoController@getImportCSV');
    Route::post('/videos/import', 'VideoController@postImport');
    Route::get('/videos/import', 'VideoController@getImport');
    Route::get('/videos/create', 'VideoController@getCreate');
    Route::post('/videos/create', 'VideoController@postCreate');
    Route::get('/videos/delete/{id}', 'VideoController@getDelete');   
    Route::post('/videos/delete', 'VideoController@postDelete');    
    Route::get('/videos/update/{id}', 'VideoController@getUpdate');
    Route::post('/videos/update/{id}', 'VideoController@postUpdate');
    
    Route::get('/categories', 'CategoryController@index');
    Route::get('/categories/create', 'CategoryController@getCreate');
    Route::post('/categories/create', 'CategoryController@postCreate');
    Route::get('/categories/update/{id}', 'CategoryController@getUpdate');
    Route::post('/categories/update/{id}', 'CategoryController@postUpdate');
    Route::get('/categories/delete/{id}', 'CategoryController@getDelete');
    Route::get('/categories/slug', 'CategoryController@updateSlug');
    
    Route::get('/tag', 'TagController@index');
    Route::get('/tag/create', 'TagController@getCreate');
    Route::post('/tag/create', 'TagController@postCreate');
    Route::get('/tag/update/{id}', 'TagController@getUpdate');
    Route::post('/tag/update/{id}', 'TagController@postUpdate');
    Route::get('/tag/delete/{id}', 'TagController@getDelete');
    
    Route::get('/page', 'PageController@index');
    Route::get('/page/create', 'PageController@getCreate');
    Route::post('/page/create', 'PageController@postCreate');
    Route::get('/page/update/{id}', 'PageController@getUpdate');
    Route::post('/page/update/{id}', 'PageController@postUpdate');
    Route::get('/page/delete/{id}', 'PageController@getDelete');
    
    Route::get('/advertise', 'AdvertiseController@index');
    Route::get('/advertise/create', 'AdvertiseController@getCreate');
    Route::post('/advertise/create', 'AdvertiseController@postCreate');
    Route::get('/advertise/update/{id}', 'AdvertiseController@getUpdate');
    Route::post('/advertise/update/{id}', 'AdvertiseController@postUpdate');
    Route::get('/advertise/delete/{id}', 'AdvertiseController@getDelete');
    
    Route::get('/source-site', 'SourceSiteController@index');
    Route::get('/source-site/create', 'SourceSiteController@getCreate');
    Route::post('/source-site/create', 'SourceSiteController@postCreate');
    Route::get('/source-site/update/{id}', 'SourceSiteController@getUpdate');
    Route::post('/source-site/update/{id}', 'SourceSiteController@postUpdate');
    Route::get('/source-site/delete/{id}', 'SourceSiteController@getDelete'); 
  });
});