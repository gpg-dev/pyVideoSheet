<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
use App\Models\SourceSite;

class ReportController extends Controller {
  
  protected function template($view) {
    return 'backend.report.' . $view;
  }
  
  public function category() {    
    $top_category = Category::where('isDeleted', '=', 0)
                ->orWhereNull('isDeleted')
                ->orderBy('numOfClicks', 'DESC')
                ->paginate(20);

    return View($this->template('category'))
                    ->with('top_category', $top_category);
  }
  
  public function source() {    
    $top_source = Video::join(SourceSite::TABLE . ' AS ss', Video::TABLE . '.sourceSiteId', '=', 'ss.id')
                ->selectRaw('SUM('. Video::TABLE . '.numOfClicks) AS total, '. Video::TABLE . '.sourceSiteId, ss.title')
                ->orderBy('total', 'DESC')
                ->groupBy(Video::TABLE . '.sourceSiteId', 'ss.title')
                ->paginate(20);

    return View($this->template('source'))
                    ->with('top_source', $top_source);
  }
}
