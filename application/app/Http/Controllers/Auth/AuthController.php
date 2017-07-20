<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator, Auth, Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'dashboard';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function login(Request $request){

        if($request->isMethod('post')){
            $rules = [
                'email' => 'email|required',
                'password' => 'required|min:6'
            ];
            $validator = Validator::make($request->all(), $rules);
            if (!$validator->fails()) {

                //we are trying to authenticate user via email
                if (Auth::attempt(['email' => trim($request->email), 'password' => $request->password], $request->remember)) {

                    $user = Auth::user();

                    session()->put([
                        'lastLogin' => Carbon::parse($user->last_login)->format('d M, Y @ h:i:s A')
                    ]);

                    $user->last_login = Carbon::now();
                    $user->save();

                    return redirect()->intended('dashboard');
                }else{
                    session()->flash('toast', toastMessage('Incorect email or password', 'error'));
                    return view('auth.login')->withInput($request->all());
                }
            } else {
                session()->flash('toast', toastMessage('Validation error occurred', 'error'));
                return view('auth.login')->withErrors($validator->errors())->withInput($request->all());
            }
        }else{
            return view('auth.login');
        }
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->flush();
        $request->session()->flash('toast', toastMessage('You have successfully logout!', 'success'));
        return redirect('/login');
    }
}
