<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertise extends Model
{
    public $table = "advertises";
    const TABLE = "advertises";
    protected $fillable = ['name', 'slug', 'content', 'type', 'sort', 'isActive', 'isDeleted', 'created_at', 'updated_at'];
    public static $ruleCreate = array(
        'name' => 'required|max:150|unique:advertises',
        'content' => 'required',
        'type' => 'required'
    );
    public static $rule = array(
        'name' => 'required|max:150',
        'content' => 'required',
        'type' => 'required'
    );
}
