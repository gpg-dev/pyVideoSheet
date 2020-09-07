<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\Category;
use App\Models\SourceSite;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Auth;

/**
 * Description of SiteController
 *
 * @author hoangtuan438
 */
class SiteController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
    protected function template($view){
       return 'backend.site.'.$view;
    }
  
    public function index(){
        $top_category = Category::where('isDeleted', 0)
                ->orWhereNull('isDeleted')
                ->orderBy('numOfClicks', 'DESC')
                ->limit(10)
                ->get();
        
        $top_source = SourceSite::leftJoin(Video::TABLE . ' AS v', SourceSite::TABLE . '.id', '=', 'v.sourceSiteId')
                ->selectRaw('SUM(v.numOfClicks) AS total, '. SourceSite::TABLE . '.id, '. SourceSite::TABLE . '.title')
                ->orderBy('total', 'DESC')
                ->groupBy(SourceSite::TABLE . '.id', SourceSite::TABLE . '.title')
                ->limit(10)
                ->get();
        $no_categories = Category::where('isDeleted', '0')->count();
        $no_sourcesite = SourceSite::where('isDeleted', '=', 0)->count();
        
        return View($this->template('index'))
                ->with('top_category', $top_category)
                ->with('top_source', $top_source)
                ->with('no_categories', $no_categories)
                ->with('no_sourcesite', $no_sourcesite);
    }
  
    public function getLogin(){    
        return View($this->template('login'));
    }
    /*
     * 
     */
    public function postLogin(Request $req){    
        $validator = Validator::make($req->all(), UserModel::$loginRules);
        if($validator->passes()){
            $user = UserModel::where('email', '=', $req->email)->first();
            if($user){
                if($user->role == UserModel::ADMIN){
                    if($user->authenticate($req->password)){            
                        Auth::login($user);
                        return redirect(action('Backend\SiteController@index'));
                    }else{
                        $validator->errors()->add('password', 'Password doesn\'t match');
                        return back()->withErrors($validator);         
                    }
                }else{
                    $validator->errors()->add('email', 'Your account isn\'t allowed to access this page');
                    return back()->withErrors($validator);         
                }
            }else{
                $validator->errors()->add('email', 'Email is not registered!');
                return back()->withErrors($validator);         
            }            
        }else{
          return back()->withErrors($validator);         
        }
    }
    
    public function getInforUser(){
        return View($this->template('information'))
                ->with('user', Auth::user());
    }
    
    public function postInforUser(Request $req){
        if(!Auth::check()){
            return redirect()->guest('backend/login');
        }
        
        $validator = Validator::make($req->all(), UserModel::$changeInfo);
        if($validator->passes()){
            $user = UserModel::where('email', '=', Auth::user()->email)->first();
            if($user->authenticate($req->old_password)){
                if(!empty($req->password)){
                    $user->password = $req->password;
                }
                
                if($user->email != $req->email){
                    $user->email = $req->email;
                    $user->save();
                    Auth::logout();
                    return redirect(action('Backend\SiteController@getLogin'));
                }
                $user->save();
                return View($this->template('information'))->with('user', Auth::user())
                        ->with('success', 'Change Information Success!');
            }else{
                $validator->errors()->add('password', 'Old Password doesn\'t match');
                return back()->withErrors($validator);         
            }
        }
        return Back()->withErrors($validator);
    }

    public function logout(){    
        if(Auth::check())Auth::logout();
        return redirect(action('Backend\SiteController@getLogin'));
    }
}
