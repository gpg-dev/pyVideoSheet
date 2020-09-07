<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
  public $table = "videos_tags";
  const TABLE = "videos_tags";
   public function video() {
      return $this->belongsTo('App\Models\Video', 'videoId');
  }
   public function category() {
      return $this->belongsTo('App\Models\Tag', 'tagId');
  }
}
