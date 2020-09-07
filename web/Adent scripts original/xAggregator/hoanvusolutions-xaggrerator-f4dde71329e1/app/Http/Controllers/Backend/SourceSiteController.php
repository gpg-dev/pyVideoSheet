<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SourceSite;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DefineConfig;

class SourceSiteController extends Controller {
  
  protected function template($view) {
    return 'backend.sourceSite.' . $view;
  }
  
  public function index() {
    $sourceSites = SourceSite::leftJoin(Video::TABLE . ' AS v', SourceSite::TABLE . '.id', '=', 'v.sourceSiteId')
            ->where(SourceSite::TABLE . '.isDeleted', '0');
    $searchParams = [
        'title' => '',
        'link'  => ''
    ];
    $parameter_order = '';
    
    if (isset($_GET['title']) && $_GET['title']) {
        $sourceSites = $sourceSites->where(SourceSite::TABLE . '.title', 'like', '%' . $_GET['title'] . '%');
        $searchParams['title'] = $_GET['title'];
        $parameter_order .= 'title=' . $_GET['title'] . '&';
    }
    if (isset($_GET['link']) && $_GET['link']) {
        $sourceSites = $sourceSites->where('link', 'like', '%' . $_GET['link'] . '%');
        $searchParams['link'] = $_GET['link'];
        $parameter_order .= 'link=' . $_GET['link'] . '&';
    }

    $sourceSites = $sourceSites
            ->selectRaw('SUM(v.numOfClicks) AS total, ' . SourceSite::TABLE . '.id, ' . 
                    SourceSite::TABLE . '.title, ' . SourceSite::TABLE . '.link, ' . SourceSite::TABLE . '.formatCSVFrom')
            ->groupBy(SourceSite::TABLE . '.id', SourceSite::TABLE . '.title', SourceSite::TABLE . '.link', 
                    SourceSite::TABLE . '.formatCSVFrom', SourceSite::TABLE . '.isDeleted');
    if(isset($_GET['order'])){
        if(isset($_GET['sort'])){
            $sourceSites = $sourceSites->orderBy($_GET['order'], $_GET['sort']);
        }else{
            $sourceSites = $sourceSites->orderBy($_GET['order'], 'ASC');
        }
    }else{
        $sourceSites = $sourceSites->orderBy('title', 'ASC');
    }
    $sourceSites = $sourceSites->paginate(20);

    return View($this->template('index'))
            ->with('sourceSites', $sourceSites)
            ->with('searchParams', $searchParams)
            ->with('parameter_order', $parameter_order);
  }
  
  public function getUpdate($id){
    $model = SourceSite::find($id);
    if(!$model){
      return redirect('404');
    }
    
    $formatCSV = DefineConfig::formatCSV;
    return View($this->template('form'))
            ->with('model', $model)
            ->with('formatCSV', $formatCSV);
  }
  
  public function postUpdate($id, Request $req){
    $model = SourceSite::find($id);
    if(!$model){
        return redirect('404');
    }
    $formatCSV = DefineConfig::formatCSV;
    if($model->title == $req->title){
        $validator = Validator::make($req->all(), SourceSite::$rule);
    }else{
        $validator = Validator::make($req->all(), SourceSite::$ruleCreate);
    }
    if ($validator->passes()) {
        $model->fill($req->all());
        if($model->save()){
            return View($this->template('form'))->with('model', $model)->with('success', 'Update Source Site is Successfull.')
                    ->with('formatCSV', $formatCSV);
        }
    } else {
        return back()->withErrors($validator)
                ->with('formatCSV', $formatCSV);
    }
  }
  
  public function getCreate(){
    $model = new SourceSite;
    $formatCSV = DefineConfig::formatCSV;
    return View($this->template('form'))
            ->with('model', $model)
            ->with('formatCSV', $formatCSV);
  }
  
  public function postCreate(Request $req){
    $validator = Validator::make($req->all(), SourceSite::$ruleCreate);
    if ($validator->passes()) {
      $model = new SourceSite;
      if(!$model){
        return redirect('404');
      }
      $model->fill($req->all());
      if($model->save()){
        return redirect(action('Backend\SourceSiteController@index'));
      }
    } else {
      return back()->withErrors($validator)->withInput($req->input());
    }           
  }
  
  public function getDelete($id){
    $model = SourceSite::find($id);
    if(!$model){
      return redirect('404');
    }
    $model->isDeleted = 1;
    $model->save();
    return back();
  }
}
