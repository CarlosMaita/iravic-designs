<?php

namespace App\Http\Middleware;

use App\Repositories\Eloquent\BoxRepository;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForCreateBox
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
        
        /**
         * Un usuario no puede tener mas de 1 caja abierta
         */
        if ($this->boxRepository->getOpenByUserId($user->id)) {
            flash("Usted ya posee una caja abierta.")->warning();
            return redirect()->route('cajas.index');
        }

        return $next($request);
    }
}
