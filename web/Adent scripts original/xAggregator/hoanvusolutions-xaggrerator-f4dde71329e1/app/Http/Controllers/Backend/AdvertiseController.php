<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Advertise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertiseController extends Controller {
  
  protected function template($view) {
    return 'backend.advertise.' . $view;
  }
  
  public function index() { 
    $advertises = Advertise::where('isDeleted', '=', 0);
    $searchParams = [
        'name' => ''
    ];
    
    if (isset($_GET['name']) && $_GET['name']) {
        //dd($_GET['title']);
        $advertises = $advertises->where('name', 'name', '%' . $_GET['name'] . '%');
        $searchParams['name'] = $_GET['name'];
    }

    $advertises = $advertises->orderBy('name', 'ASC')->paginate(20);

    return View($this->template('index'))
                    ->with('advertises', $advertises)->with('searchParams', $searchParams);
  }
  
  public function getUpdate($id){
    $model = Advertise::find($id);
    if(!$model){
      return redirect('404');
    }
    
    return View($this->template('form'))->with('model', $model);
  }
  
  public function postUpdate($id, Request $req){
    $model = Advertise::find($id);
    if(!$model){
        return redirect('404');
    }
    if($model->name == $req->name){
        $validator = Validator::make($req->all(), Advertise::$rule);
    }else{
        $validator = Validator::make($req->all(), Advertise::$ruleCreate);
    }
    
    if ($validator->passes()) {
      $model->fill($req->all());
      if($model->save()){
        return View($this->template('form'))->with('model', $model)->with('success', 'Update Advertise is Successfull.');
      }
    } else {
      return back()->withErrors($validator);
    }
  }
  
  public function getCreate(){
    $model = new Advertise;        
    return View($this->template('form'))->with('model', $model);
  }
  
  public function postCreate(Request $req){
    $validator = Validator::make($req->all(), Advertise::$ruleCreate);
    if ($validator->passes()) {
        $model = new Advertise;
        if(!$model){
            return redirect('404');
        }
        $model->fill($req->all());
        $model->slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $model->name));
        if($model->save()){
            return redirect(action('Backend\AdvertiseController@index'));
        }
    } else {
        return back()->withErrors($validator)->withInput($req->input());
    }
  }
  
  public function getDelete($id){
    $model = Advertise::find($id);
    if(!$model){
      return redirect('404');
    }
    $model->isDeleted = 1;
    $model->save();
    return back();
  }
}
