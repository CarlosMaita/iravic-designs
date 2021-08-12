<?php

namespace App\Http\Middleware;

use App\Repositories\Eloquent\BoxRepository;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForCreateOrder
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
            flash("Usted no tiene una caja abierta para registrar pedidos.")->warning();
            return redirect()->route('pedidos.index');
        }

        return $next($request);
    }
}
