<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerLoginController extends Controller
{
    use AuthenticatesUsers;
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'e/dashboard'; // Define la ruta de redirección para clientes


     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout'); // Aplica el middleware 'guest:customer' excepto para la ruta de logout
    }

    public function showLoginForm()
    {
        return view('ecommerce.auth.login');
    }

    
    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        
        // Check if customer exists with this email
        $customer = \App\Models\Customer::where('email', $credentials['email'])->first();
        
        if (!$customer) {
            // Customer doesn't exist - suggest Google registration if their email looks like a Google account
            if (str_ends_with($credentials['email'], '@gmail.com')) {
                Session::flash('message', 'No encontramos una cuenta con este correo. Si tienes una cuenta de Google, puedes registrarte usando el botón "Continuar con Google".');
            } else {
                Session::flash('message', 'No encontramos una cuenta con este correo electrónico. Por favor, regístrate primero.');
            }
            return false;
        }
        
        return $this->guard('customer')->attempt($credentials, $request->boolean('remember'));
    }


    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email'; 
    }

       /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->guard('customer')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect()->route('customer.login.form'); // Redirige a la ruta de login de clientes
    }

      /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('customer'); 
    }


}
