<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    // protected function create(array $data)
    // {
    //     return User::create([
    //         'name' => $data['name'],
    //         'username'=>$data['username'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($data['password']),
    //     ]);
    // }

    function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', "unique:users,username,$request->id"],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,$request->id"],
            'role' => ['required',],
            'company' => ['required',],
            'mobile' => ['min:10', 'max:10'],
            'password' => ['string', 'min:8'],
        ]);


        if ($request->id)
            $user = User::find($request->id);
        else


            $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        $user->mobile = $request->mobile;
        $user->company_id = $request->company;
        $user->role = $request->role;

        $user->gst = $request->gst_permission;
        $user->vehicle = $request->vehicle_permission;
        $user->jobcard = $request->jobcard_permission;
        $user->reward = $request->reward_permission;




        $user->password = Hash::make('12345678');


        $user->save();
        return 'success';
        // if($user->save()){
        //     return redirect()->back()->with('success','You are now Successfully Registered...');
        // }else{
        //     return redirect()->back()->with('error','Failed to resister, Please try agian!');
        // }
    }



    public function getUsers()
    {

        return User::where('role', '!=', 3)->orderBy('id', 'DESC')->get();
    }
    public function deleteUser(Request $request)
    {

        if ($request->id) {
            $user = User::find($request->id);
            $user->delete();
            return 'success';
        } else {

            return "failed";
        }
    }
}
