<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\VideoObserver;
use App\Models\VideoCategory;
use App\Models\SourceSite;
use App\Services\UploadHandler;
use Illuminate\Support\Facades\URL;
use Datetime;

class Video extends Model {

  public $table = "videos";
  const TABLE = "videos";
  protected $fillable = ['sourceSiteId', 'videoId', 'url', 'title', 'slug', 'description', 'image', 'duration', 'numOfClicks', 'isActive', 'created_at', 'updated_at'];

  public static $rule = array(
    'title' => 'required|max:150',
    'url' => 'required|max:150'
  );
  public static $ruleCreate = array(
    'title' => 'required|max:150|unique:videos',
    'url' => 'required|max:150|unique:videos'
  );

  public static function boot() {
    parent::boot();
    self::observe(new VideoObserver());
  }

  public function getImage(){
    if($this->image){
        if(strpos($this->image, 'http') !== false){
            $handle = curl_init($this->image);
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            if($httpCode == 404) {
                return URL::to(UploadHandler::UPLOAD_URL . 'images/noimage.jpg');
            }

            return $this->image;
        }
        return UploadHandler::UPLOAD_URL . 'images/'.$this->image;
    }
    return URL::to(UploadHandler::UPLOAD_URL . 'images/noimage.jpg');
  }

  public function getCatName(){
    $cats = VideoCategory::join(Category::TABLE . ' AS c', VideoCategory::TABLE . '.catId', '=', 'c.Id')
            ->where(function($q){
              $q->where('c.isDeleted', '=', 0)
              ->orWhereNull('c.isDeleted');
            })
            ->where(VideoCategory::TABLE . '.videoId', $this->id)->pluck('c.title')->toArray();

    $catName = implode(', ', $cats);
    if(strlen($catName) > 70){
        $catName = substr($catName, 0, 69) . '...';
    }
    return $catName;
  }

  public function getDateAgo(){
    return $this->time_elapsed_string($this->created_at);
  }

  public function getSource(){
    $title = $this->title;
    if(strlen($title) > 70){
        return substr($title, 0, 50) . ' ...';
    }else{
        return $title;
    }
  }

   public function videoCat() {
      return $this->hasMany('App\Models\VideoCategory', 'videoId');
  }

  function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);

    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

  public static function get_remote_data($url, $post_paramtrs=false)
  {
      $c = curl_init();
      curl_setopt($c, CURLOPT_URL, $url);
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      if($post_paramtrs)
      {
          curl_setopt($c, CURLOPT_POST,TRUE);
          curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&".$post_paramtrs );
      }
      curl_setopt($c, CURLOPT_SSL_VERIFYHOST,false);
      curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);
      curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
      curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
      curl_setopt($c, CURLOPT_MAXREDIRS, 10);
      $follow_allowed= ( ini_get('open_basedir') || ini_get('safe_mode')) ? false:true;
      if ($follow_allowed)
      {
          curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
      }
      curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
      curl_setopt($c, CURLOPT_REFERER, $url);
      curl_setopt($c, CURLOPT_TIMEOUT, 60);
      curl_setopt($c, CURLOPT_AUTOREFERER, true);
      curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
      $data=curl_exec($c);
      $status=curl_getinfo($c);
      curl_close($c);
      preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si',  $status['url'],$link); $data=preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si','$1=$2'.$link[0].'$3$4$5', $data);   $data=preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si','$1=$2'.$link[1].'://'.$link[3].'$3$4$5', $data);
      if($status['http_code']==200)
      {
          return $data;
      }
      elseif($status['http_code']==301 || $status['http_code']==302)
      {
          if (!$follow_allowed)
          {
              if (!empty($status['redirect_url']))
              {
                  $redirURL=$status['redirect_url'];
              }
              else
              {
                  preg_match('/href\=\"(.*?)\"/si',$data,$m);
                  if (!empty($m[1]))
                  {
                      $redirURL=$m[1];
                  }
              }
              if(!empty($redirURL))
              {
                  return  call_user_func( __FUNCTION__, $redirURL, $post_paramtrs);
              }
          }
      }
      return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:".json_encode($status)."<br/><br/>Last data got<br/>:$data";
  }
}
