<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use \App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\UploadHandler;

class CategoryController extends Controller {
  
  protected function template($view) {
    return 'backend.category.' . $view;
  }
  public function index() {
    $cats = Category::where('isDeleted', '=', 0);
    $searchParams = [
        'title' => ''
    ];
    $parameter_order = '';
    
    if (isset($_GET['title']) && $_GET['title']) {
        $cats = $cats->where('title', 'like', '%' . $_GET['title'] . '%');
        $searchParams['title'] = $_GET['title'];
        $parameter_order .= 'title=' . $_GET['title'] . '&';
    }
    
    if(isset($_GET['order'])){
        if(isset($_GET['sort'])){
            $cats = $cats->orderBy($_GET['order'], $_GET['sort']);
        }else{
            $cats = $cats->orderBy($_GET['order'], 'ASC');
        }
    }else{
        $cats = $cats->orderBy('title', 'ASC');
    }
            
    $cats = $cats->paginate(20);

    return View($this->template('index'))
            ->with('cats', $cats)
            ->with('searchParams', $searchParams)
            ->with('parameter_order', $parameter_order);
  }
  
  public function getUpdate($id){
    $model = Category::find($id);    
    if(!$model){
      return redirect('404');
    }
    
    return View($this->template('form'))->with('model', $model);
  }
  
  public function postUpdate($id, Request $req){
    $validator = Validator::make($req->all(), Category::$rule);
    if ($validator->passes()) {
      $model = Category::find($id);    
      if(!$model){
        return redirect('404');
      }
      $oldImage = $model->image;
      $data = $req->all();
      $data['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['title']));
      $model->fill($data);
      if (isset($_FILES['file']) && $_FILES['file']['name']) {
        $statusUpload = UploadHandler::uploadImage($_FILES['file'], 'categories');
        if ($statusUpload['isSuccess']){
          if($oldImage && file_exists(UploadHandler::getUploadPath() . 'categories/' . $oldImage)){
            unlink(UploadHandler::getUploadPath() . 'categories/' . $oldImage);
          }
          $model->image = $statusUpload['data'];
        } else {
          $validator->errors()->add('file', $statusUpload['data']);
          return back()->withErrors($validator);
        }
      }
      if($model->save()){
        return back();
      }
    } else {
      return back()->withErrors($validator);
    }
  }
  
  public function getCreate(){
    $model = new Category;        
    return View($this->template('form'))->with('model', $model);
  }
  
  public function postCreate(Request $req){
    $validator = Validator::make($req->all(), Category::$ruleCreate);
    if ($validator->passes()) {
      $model = new Category;    
      if(!$model){
        return redirect('404');
      }
      $data = $req->all();
      $data['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['title']));
      $model->fill($data); 
      if (isset($_FILES['file']) && $_FILES['file']['name']) {
        $statusUpload = UploadHandler::uploadImage($_FILES['file'], 'categories');
        if ($statusUpload['isSuccess']) {
          $model->image = $statusUpload['data'];
        } else {
          $validator->errors()->add('file', $statusUpload['data']);
          return back()->withErrors($validator);
        }
      }
      if($model->save()){
        return redirect(action('Backend\CategoryController@index'));
      }
    } else {
      return back()->withErrors($validator)->withInput($req->input());
    }
  }
  
  public function updateSlug(){
        $cats = Category::where('isDeleted', '=', 0)->orWhereNull('isDeleted')->get();
        
        foreach($cats as $cat){
            $cat->slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $cat->title));
            if(!$cat->save()){
                echo $cat->title;
            }
        }
  }
  
  public function getDelete($id){
    $model = Category::find($id);
    if(!$model){
      return redirect('404');
    }
    $model->isDeleted = 1;
    $model->save();
    return back();
  }
}
