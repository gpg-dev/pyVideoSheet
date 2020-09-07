<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
/**
 * Description of VideoController
 *
 * @author pc
 */
class PageController extends Controller{
    //put your code here
    protected function template($view){
        return 'frontend.page.'.$view;
    }
    
    public function getPages($slug) {
        $page = Page::where('slug', $slug)->firstOrFail();
        
        return View($this->template('page'))
                ->with('page', $page);
    }
    
    public function contactUs(){
        $contact_type = \App\Services\DefineConfig::contact_type;
        return View($this->template('contactUs'))
                ->with('contact_type', $contact_type);
    }
}
