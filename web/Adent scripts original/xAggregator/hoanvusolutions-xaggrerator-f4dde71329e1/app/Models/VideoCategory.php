<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
  public $table = "videos_categories";
  const TABLE = "videos_categories";
   public function video() {
      return $this->belongsTo('App\Models\Video', 'videoId');
  }
   public function category() {
      return $this->belongsTo('App\Models\Category', 'catId');
  }
}
