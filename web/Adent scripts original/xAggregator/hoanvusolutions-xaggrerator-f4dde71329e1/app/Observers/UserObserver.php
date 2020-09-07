<?php
namespace App\Observers;

use App\Models\UserModel;
use Hash;

class UserObserver {
				
    public function saving($model)
    {
      if($model->password){
        if ($model->id) {
          $userModel = UserModel::where('id', '=', $model->id)->first();
          if($userModel){
            if ($model->password != $userModel->password) {
              $model->password = Hash::make($model->password);
            } else {
              $model->password = $userModel->password;
            }
          }        
        }else{
          $model->password = Hash::make($model->password);
        } 
      }
      
      $model->name = $model->firstName.' '.$model->lastName;         
    }     
     public function deleted($model){      
      if($model->avatar){
        UserModel::removeImage($model->avatar);        
      }
    }
}
?>