<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $table = "pages";
    const TABLE = "pages";
    protected $fillable = ['title', 'slug', 'content', 'isActive', 'isDeleted', 'created_at', 'updated_at'];
    public static $ruleCreate = array(
      'title' => 'required|max:150|unique:pages'
    );
    public static $rule = array(
      'title' => 'required|max:150'
    );
}
