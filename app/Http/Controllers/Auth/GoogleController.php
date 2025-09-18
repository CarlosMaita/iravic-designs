<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if customer exists with this email
            $customer = Customer::where('email', $googleUser->email)->first();
            
            if ($customer) {
                // Customer exists - check if Google is verified
                if (!$customer->google_verified) {
                    // This is an existing customer trying to login with Google for the first time
                    // Update their Google verification status
                    $customer->google_verified = true;
                    $customer->google_id = $googleUser->id;
                    $customer->save();
                }
                
                // Log the customer in
                Auth::guard('customer')->login($customer);
                return redirect()->route('customer.dashboard')->with('success', 'Bienvenido de vuelta!');
            } else {
                // Customer doesn't exist - redirect to registration with Google data
                Session::put('google_user', [
                    'google_id' => $googleUser->id,
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar,
                ]);
                
                return redirect()->route('customer.google.register.form')->with('message', 'Para completar tu registro, necesitamos algunos datos adicionales.');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('customer.login.form')->with('error', 'Error al autenticarse con Google. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Show Google registration form
     */
    public function showGoogleRegistrationForm()
    {
        $googleUser = Session::get('google_user');
        
        if (!$googleUser) {
            return redirect()->route('customer.register.form')->with('error', 'Sesión de Google expirada. Por favor, intenta nuevamente.');
        }
        
        return view('ecommerce.auth.google-register', compact('googleUser'));
    }

    /**
     * Complete Google registration
     */
    public function completeGoogleRegistration(Request $request)
    {
        $googleUser = Session::get('google_user');
        
        if (!$googleUser) {
            return redirect()->route('customer.register.form')->with('error', 'Sesión de Google expirada. Por favor, intenta nuevamente.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        // Check if email is already taken by another user
        $existingCustomer = Customer::where('email', $googleUser['email'])->first();
        if ($existingCustomer) {
            return redirect()->route('customer.login.form')->with('error', 'Ya existe una cuenta con este correo electrónico. Por favor, inicia sesión normalmente.');
        }

        // Create new customer
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $googleUser['email'],
            'password' => Hash::make($request->password),
            'google_verified' => true,
            'google_id' => $googleUser['google_id'],
            'qualification' => 'Bueno', // Default qualification
        ]);

        // Log the customer in
        Auth::guard('customer')->login($customer);
        
        // Clear Google session data
        Session::forget('google_user');
        
        return redirect()->route('customer.dashboard')->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');
    }
}
