<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use App\Models\UserModel;
/**
 * Description of AuthenticateAdmin
 *
 * @author hoangtuan438
 */
class AuthenticateAdmin {
  protected $auth;
  public function __construct(Guard $auth)
  {
      $this->auth = $auth;
  }
  public function handle($request, Closure $next)
  {     
    if ($this->auth->guest()) {
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        } else {
            return redirect()->guest('backend/login');
        }
    }else{
      if(Auth::user()->role != UserModel::ADMIN){
        return redirect()->guest('backend/login');
      }
    }   
    return $next($request);
  }
}
