<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function redirectTo(){
            if(Auth()->user()->role == 1){//1->admin
                return route('admin.dashboard');
            }elseif(Auth()->user()->role == 2){//2->user
                return route('admin.dashboard');
            }elseif(Auth()->user()->role == 3){//3->superadmin
                return route('admin.dashboard');
            }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){

        $input=$request->all();
        $this->validate($request,[

        'username'=>'required',
        'password'=>'required'
        ]);
        $remember_me = $request->has('remember') ? true : false;

        if(Auth::attempt(['username' => $input['username'], 'password' => $input['password']], $remember_me )){

            if(auth()->user()->role==1){
                return redirect()->route('admin.dashboard');

            }

            elseif(auth()->user()->role==2){
                return redirect()->route('admin.dashboard');

            }

            elseif(auth()->user()->role==3){
                return redirect()->route('admin.dashboard');

            }

        }else{
            return redirect()->route('login')->withErrors(['Login Credentials Do Not Match..Please Try Again..']);

        }

    }

}
