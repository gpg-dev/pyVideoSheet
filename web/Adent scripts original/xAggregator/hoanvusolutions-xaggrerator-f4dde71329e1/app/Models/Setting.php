<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\SettingObserver;
use App\Services\UploadHandler;

class Setting extends Model
{
    public $table = "settings";
    const TABLE = "settings";
    protected $fillable = ['key', 'content', 'type', 'isDeleted'];
    public static function boot() {
      parent::boot();
      self::observe(new SettingObserver());
    }
    public static $rule = array(
        'key' => 'required',
        'content' => 'required'
    );
    public static $ruleCreate = array(
        'key' => 'required|unique:settings',
        'content' => 'required'
    );
}
