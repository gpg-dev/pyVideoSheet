<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $table = "tags";
    const TABLE = "tags";
    protected $fillable = ['title', 'isDeleted', 'created_at', 'updated_at'];
    public static $ruleCreate = array(
      'title' => 'required|max:150|unique:tags'
    );
    public static $rule = array(
      'title' => 'required|max:150'
    );
}
