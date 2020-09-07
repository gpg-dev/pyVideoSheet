<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\CategoryObserver;
use App\Services\UploadHandler;

class Category extends Model
{
    public $table = "categories";
    const TABLE = "categories";
    protected $fillable = ['title', 'slug', 'isActive', 'isDeleted', 'image', 'numOfClicks', 'created_at', 'updated_at'];
    public static $rule = array(
      'title' => 'required|max:150',
    );
    public static $ruleCreate = array(
      'title' => 'required|max:150|unique:categories',
    );
    
    public static function boot() {
      parent::boot();
      self::observe(new CategoryObserver());      
    }
    
    public function getImage(){
      if($this->image){
        if(strpos($this->image, 'http') !== false){
          return  $this->image;
        }          
        return UploadHandler::UPLOAD_URL.'categories/'.$this->image; 
      }
      return '';
    }
}
