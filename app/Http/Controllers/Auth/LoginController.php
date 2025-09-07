<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Role;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = ($request->has('remember_me')) ? true : false;
        #validar existencia de usuario
        $user = User::where('email',$credentials['email'])->first();
        // Verifica si el usuario existe
        if ($user) {
            // Verifica si existe un rol asignado
            $role_user = $user->roles;
            if (count($role_user) > 0 ){
                if (Auth::attempt($credentials, $remember)) {
                    return redirect($this->redirectTo);
                }
                return back()->with(['message' => 'Credenciales inválidas.']);
            }else{
                // El usuario no tiene rol vinvulado, muestra un mensaje de error
                return redirect()->back()->with(['message' => 'El rol no existe o no cuenta con acceso.']);
            }
    
        }else{
             // El usuario no existe, muestra un mensaje de error
            return redirect()->back()->with(['message' => 'El usuario no existe.']);
        }

    }
}
