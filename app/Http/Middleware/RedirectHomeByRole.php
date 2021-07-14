<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectHomeByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('graduaciones.index');
        } else {
            return redirect()->route('persona.perfil');
        }
        */

        return $next($request);
    }
}
