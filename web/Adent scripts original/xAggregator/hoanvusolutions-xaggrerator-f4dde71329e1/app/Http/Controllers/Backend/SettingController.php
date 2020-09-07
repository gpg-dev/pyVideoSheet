<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\UploadHandler;

class SettingController extends Controller {
  
  protected function template($view) {
    return 'backend.setting.' . $view;
  }
  
  public function index() {
        $arrSettings = array();
        $arrMeta = array();
        $setting = Setting::where('isDeleted', '=', 0)->orWhereNull('isDeleted')->get();
        foreach($setting as $value){
            if($value->type != 'meta'){
                $arrSettings[$value->key] = $value->content;
            }else{
                $arrMeta[] = $value;
            }
        }
        
        return View($this->template('index'))
            ->with('model', new Setting)
            ->with('settings', $arrSettings)
            ->with('metas', $arrMeta);
  }
  
    public function postUpdate(Request $req){
        if(isset($req->header_logo) && $_FILES['header_logo']){
            $rule_header = Setting::$rule;
            $header_logo = Setting::where('isDeleted', '=', '0')->where('key', 'header_logo')->first();
            if(!$header_logo){
                $header_logo = new Setting();
                $header_logo->key = 'header_logo';
                $rule_header = Setting::$ruleCreate;
            }
            $statusUpload = UploadHandler::uploadImage($_FILES['header_logo'], 'images');
            if ($statusUpload['isSuccess']) {
                $header_logo->content = $statusUpload['data'];
            } else {
                $validation->errors()->add('header_logo', $statusUpload['data']);
                return back()->withErrors($validator);
            }
            $header_logo->type = '';
            $validate = Validator::make(array('key' => 'header_logo', 'content' => $header_logo->content), $rule_header);
            if($validate->passes()){
                $header_logo->save();
            }else{
                return back()->withErrors($validate);
            }
        }
      
        if(isset($req->footer_logo) && $_FILES['footer_logo']){
            $rule_footer = Setting::$rule;
            $footer_logo = Setting::where('isDeleted', '=', '0')->where('key', 'footer_logo')->first();
            if(!$footer_logo){
                $footer_logo = new Setting();
                $footer_logo->key = 'footer_logo';
                $rule_footer = Setting::$ruleCreate;
            }
            $statusUpload = UploadHandler::uploadImage($_FILES['footer_logo'], 'images');
            if ($statusUpload['isSuccess']) {
                $footer_logo->content = $statusUpload['data'];
            } else {
                $validation->errors()->add('footer_logo', $statusUpload['data']);
                return back()->withErrors($validator);
            }
            $footer_logo->type = '';
            $validate = Validator::make(array('key' => 'footer_logo', 'content' => $footer_logo->content), $rule_footer);
            if($validate->passes()){
                $footer_logo->save();
            }else{
                return back()->withErrors($validate);
            }
        }
      
        if(isset($req->favicon) && $_FILES['favicon']){
            $rule_favicon = Setting::$rule;
            $favicon = Setting::where('isDeleted', '=', '0')->where('key', 'favicon')->first();
            if(!$favicon){
                $favicon = new Setting();
                $favicon->key = 'favicon';
                $rule_favicon = Setting::$ruleCreate;
            }
            $validate = Validator::make(array('key' => 'favicon', 'content' => $favicon->content), $rule_favicon);
            $statusUpload = UploadHandler::uploadImage($_FILES['favicon'], 'images');
            if ($statusUpload['isSuccess']) {
                $favicon->content = $statusUpload['data'];
            } else {
                $validate->errors()->add('favicon', $statusUpload['data']);
                return back()->withErrors($validate);
            }
            $favicon->type = '';
            if($validate->passes()){
                $favicon->save();
            }else{
                return back()->withErrors($validate);
            }
        }
        
        foreach($req->input() as $key => $value){
            if(trim($key) != 'key' && trim($key) != 'content' && $key != '_token'){ // type is not meta tags
                if(!empty(trim($value))){
                    $rule = Setting::$rule;
                    $setting = Setting::where('key', trim($key))->first();
                    
                    if(!$setting){ 
                        $setting = new Setting();
                        $setting->key = $key;
                        $setting->isDeleted = 0;
                        $setting->type = '';
                        $rule = Setting::$ruleCreate;
                    }
                    $setting->content = trim($value);
                    $validate = Validator::make(array('key' => trim($key), 'content' => trim($value)), $rule);
                    if($validate->passes()){
                          $setting->save();
                    }else{
                        return back()->withErrors($validate);
                    }
                }
            }else if($key == 'key'){
                $key_meta = array();
                for($i = 0; $i < count($value); $i++){
                    if(!empty(trim($value[$i])) && !empty(trim($req->content[$i]))){
                        $rule = Setting::$rule;
                        $setting = Setting::where('key', trim($value[$i]))->first();
                        if(!$setting){
                            $setting = new Setting();
                            $setting->key = trim($value[$i]);
                            $rule = Setting::$ruleCreate;
                        }
                        $setting->content = trim($req->content[$i]);
                        $setting->isDeleted = 0;
                        $setting->type = 'meta';
                        $validate = Validator::make(array('key' => $value[$i], 'content' => trim($req->content[$i])), $rule);
                        if($validate->passes()){
                            $setting->save();
                            $key_meta[] = $value[$i];
                        }
                    }
                }
                Setting::whereNotIn('key', $key_meta)->where('type', 'meta')->delete();
            }
            unset($validate);
            unset($setting);
            unset($rule);
            unset($key_meta);
        }
        
        $arrSettings = array();
        $arrMeta = array();
        $setting = Setting::where('isDeleted', '=', 0)->orWhereNull('isDeleted')->get();
        foreach($setting as $value){
            if($value->type != 'meta'){
                $arrSettings[$value->key] = $value->content;
            }else{
                $arrMeta[] = $value;
            }
        }
    
        return View($this->template('index'))
            ->with('model', new Setting)
            ->with('settings', $arrSettings)
            ->with('metas', $arrMeta)
            ->with('success', 'Update Setting is Successfull.');
      
    }
}
