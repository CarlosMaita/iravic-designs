<?php

namespace App\Http\Middleware;

use App\Repositories\Eloquent\BoxRepository;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckOpenBox
{
    public $boxRepository;

    public function __construct(BoxRepository $boxRepository)
    {
        $this->boxRepository = $boxRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$this->boxRepository->getOpenByUserId($user->id)) {
            #verificar permiso de ver caja
            flash("Usted no tiene una caja abierta.")->warning();
            if ( !$request->user()->can('viewany', 'App\Models\Box')){
                flash( "Usted no cuenta con los permisos suficientes para ver una Caja.")->error();
                return redirect()->back();
            }
            return redirect()->route('cajas.index');

        }

        return $next($request);
    }
}
