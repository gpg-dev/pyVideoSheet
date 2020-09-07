<?php
namespace App\Observers;

use App\Models\Video;
use App\Models\VideoCategory;

class VideoObserver {
				
    public function saving($model)
    {
      
    }     
     public function deleted($model){      
      VideoCategory::where('videoId', $model->id)->delete();
    }
}
?>