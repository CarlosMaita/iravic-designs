<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    /**
     * Check customer authentication status
     * Returns authentication status and customer info if authenticated
     */
    public function authCheck(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::guard('customer')->check(),
            'customer' => Auth::guard('customer')->check() ? (function () {
                $u = Auth::guard('customer')->user();
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                ];
            })() : null
        ]);
    }
}