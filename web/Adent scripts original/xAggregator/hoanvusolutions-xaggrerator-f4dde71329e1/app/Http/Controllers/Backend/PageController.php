<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller {
  
  protected function template($view) {
    return 'backend.page.' . $view;
  }
  
  public function index() {    
    $pages = Page::where('isDeleted', '=', 0)->orWhereNull('isDeleted');
    $searchParams = [
        'title' => ''
    ];
    
    if (isset($_GET['title']) && $_GET['title']) {
        $pages = $pages->where('title', 'like', '%' . $_GET['title'] . '%');
        $searchParams['title'] = $_GET['title'];
    }

    $pages = $pages->orderBy('title', 'ASC')->paginate(20);

    return View($this->template('index'))
                    ->with('pages', $pages)->with('searchParams', $searchParams);
  }
  
  public function getUpdate($id){
    $model = Page::find($id);    
    if(!$model){
      return redirect('404');
    }
    
    return View($this->template('form'))->with('model', $model);
  }
  
  public function postUpdate($id, Request $req){
    $model = Page::find($id);
    if(!$model){
        return redirect('404');
    }
    if($model->title == $req->title){
        $validator = Validator::make($req->all(), Page::$rule);
    }else{
        $validator = Validator::make($req->all(), Page::$ruleCreate);
    }
    
    if ($validator->passes()) {
      $model->fill($req->all());
      $model->slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $model->title));
      if($model->save()){
        return View($this->template('form'))->with('model', $model)->with('success', 'Update Page is Successfull.');
      }
    } else {
      return back()->withErrors($validator);
    }
            
  }
  
  public function getCreate(){
    $model = new Page;        
    return View($this->template('form'))->with('model', $model);
  }
  
  public function postCreate(Request $req){
    $validator = Validator::make($req->all(), Page::$ruleCreate);
    if ($validator->passes()) {
        $model = new Page;
        if(!$model){
            return redirect('404');
        }
        $model->fill($req->all());
        $model->slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $model->title));
        if($model->save()){
            return redirect(action('Backend\PageController@index'));
        }
    } else {
        return back()->withErrors($validator)->withInput($req->input());
    }
  }
  
  public function getDelete($id){
    $model = Page::find($id);
    if(!$model){
      return redirect('404');
    }
    $model->isDeleted = 1;
    $model->save();
    return back();
  }
}
