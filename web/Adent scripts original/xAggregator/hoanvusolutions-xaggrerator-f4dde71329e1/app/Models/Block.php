<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    public $table = "blocks";
    const TABLE = "blocks";
    protected $fillable = ['title', 'slug', 'content', 'isActive', 'isDeleted', 'created_at', 'updated_at'];
    public static $ruleCreate = array(
        'title' => 'required|max:150|unique:blocks',
        'content' => 'required'
    );
    public static $rule = array(
        'title' => 'required|max:150',
        'content' => 'required'
    );
}
