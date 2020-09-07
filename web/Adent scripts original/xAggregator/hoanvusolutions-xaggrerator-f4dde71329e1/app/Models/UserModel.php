<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\UserObserver;
use Hash;
use App\Services\CommonService;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use URL;
use App\Services\UploadHandler;

class UserModel extends Model 
        implements AuthenticatableContract  
        ,AuthorizableContract
        ,CanResetPasswordContract
{  
  use Authenticatable, Authorizable, CanResetPassword;
  const MEMBER = 0;
  const ADMIN = 1;
  const VERIFIED = 1;
	const MALE = 'male';
  const FEMALE = 'female';
  const STATUS_APPROVE = 1;
  const STATUS_PENDING = 0;
  
  protected $fillable = array('firstName', 'lastName', 'email','username','password', 'phone', 'address', 'status', 'role', 
      'emailVerifyToken', 'emailVerified', 'createdAt', 'updatedAt', 'avatar', 'gender', 'location', 'company', 'name', 'latitude', 'longitude',
      'facebookToken', 'googleToken', 'twitterToken','twitterId');  
  protected $hidden = array('password', 'remember_token');
  
  public $table = "users";
	public static $registeredRules = array(
    'firstName' => 'sometimes|required',
    'lastName' => 'sometimes|required',
    'email' => 'required|email|unique:users,email',
    'password' => 'sometimes|required'    
  );
  
  public static $loginRules = array(    
    'email' => 'required|email',
    'password' => 'sometimes|required'    
  );
  
  public static $changeInfo = array(    
        'email' => 'required|email',
        'old_password' => 'required',
        'password' => 'min:3|confirmed',
        'password_confirmation' => 'min:3'
  );
 
  public static function boot() {
			parent::boot();
			self::observe(new UserObserver());      
  }	
  
//  public function setPasswordAttribute($value){        
//     $this->attributes['password'] = Hash::make($value);
//  }
  
  public static function getVerifyToken(){    
    $verifyToken = CommonService::getRandomString(30);
    $user = self::where('emailVerifyToken', '=', $verifyToken)->first();    
    if($user){
      return $this->getVerifyToken();
    }else{
      return $verifyToken;
    }          
  }
  
  public function authenticate($password){
    if(Hash::check($password, $this->password)){
      return true;
    }
    return false;
  }
  
  public function getAvatar(){    
    $avatar = CommonService::getFullUrl().'/public/frontend/images/user.jpg';
    if($this->avatar){      
      if(strpos($this->avatar, 'http')!==false){
        $avatar = $this->avatar;
      }else{
        $avatar = URL::asset('public/upload/avatar').'/'.$this->avatar;
      }      
    }
    return $avatar;
  }
  
  public function getStatus(){
    $status = self::getStatuses();
    return $status[$this->status];
  }
  
  public static function getGenders(){
    return [
        self::MALE => 'Male',
        self::FEMALE => 'Female'
    ];
  }
  public static function getStatuses(){
    return [
        self::STATUS_PENDING => 'pending',
        self::STATUS_APPROVE => 'approved'
    ];
  }
  public static function removeImage($imageName){
    UploadHandler::removeImage('avatar', $imageName);
  }
}