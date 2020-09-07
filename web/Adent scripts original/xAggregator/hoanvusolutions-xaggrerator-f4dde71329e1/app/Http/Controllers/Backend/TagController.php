<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use \App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\UploadHandler;

class TagController extends Controller {
  
  protected function template($view) {
    return 'backend.tag.' . $view;
  }
  
  public function index() {    
    $tags = Tag::where('isDeleted', '=', 0)->orWhereNull('isDeleted');
    $searchParams = [
        'title' => ''
    ];
    
    if (isset($_GET['title']) && $_GET['title']) {
        //dd($_GET['title']);
        $tags = $tags->where('title', 'like', '%' . $_GET['title'] . '%');
        $searchParams['title'] = $_GET['title'];
    }

    $tags = $tags->orderBy('title', 'ASC')->paginate(20);

    return View($this->template('index'))
                    ->with('tags', $tags)->with('searchParams', $searchParams);
  }
  
  public function getUpdate($id){
    $model = Tag::find($id);    
    if(!$model){
      return redirect('404');
    }
    
    return View($this->template('form'))->with('model', $model);
  }
  
  public function postUpdate($id, Request $req){
    $model = Tag::find($id);
    if(!$model){
        return redirect('404');
    }
    if($model->title == $req->title){
        $validator = Validator::make($req->all(), Tag::$rule);
    }else{
        $validator = Validator::make($req->all(), Tag::$ruleCreate);
    }
    if ($validator->passes()) {
      $model->fill($req->all());
      if($model->save()){
        return View($this->template('form'))->with('model', $model)->with('success', 'Update Tag is Successfull.');
      }
    } else {
      return back()->withErrors($validator);
    }
            
  }
  
  public function getCreate(){
    $model = new Tag;        
    return View($this->template('form'))->with('model', $model);
  }
  
  public function postCreate(Request $req){
    $validator = Validator::make($req->all(), Tag::$ruleCreate);
    if ($validator->passes()) {
      $model = new Tag;    
      if(!$model){
        return redirect('404');
      }
      $model->fill($req->all());
      if($model->save()){
        return redirect(action('Backend\TagController@index'));
      }
    } else {
      return back()->withErrors($validator)->withInput($req->input());
    }           
  }
  
  public function getDelete($id){
    $model = Tag::find($id);
    if(!$model){
      return redirect('404');
    }
    $model->isDeleted = 1;
    $model->save();
    return back();
  }
}
