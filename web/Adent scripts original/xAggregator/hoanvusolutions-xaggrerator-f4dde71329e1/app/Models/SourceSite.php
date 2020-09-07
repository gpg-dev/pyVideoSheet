<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\SourceSiteObserver;

class SourceSite extends Model
{
    public $table = "source_sites";
    const TABLE = "source_sites";
    protected $fillable = ['title', 'link', 'description', 'formatCSVFrom', 'created_at', 'updated_at', 'isDeleted'];
    public static function boot() {
      parent::boot();
      self::observe(new SourceSiteObserver());      
    }	
    public static $rule = array(
        'title' => 'required|max:150',
        'formatCSVFrom' => 'required'
    );
    public static $ruleCreate = array(
        'title' => 'required|max:150|unique:source_sites',
        'formatCSVFrom' => 'required'
    );
}
