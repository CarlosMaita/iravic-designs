<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Customer;

class DebugController extends Controller
{
    /**
     * Debug route for testing authorization
     * Returns current user permissions and capabilities
     */
    public function authDebug()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated']);
        }
        
        return response()->json([
            'user' => $user->name,
            // 'roles' => $user->roles->pluck('name'), // Disabled: roles system removed in #116
            // 'permissions' => $user->permissions(), // Disabled: permissions system removed in #116
            'can_view_customer' => $user->can('viewAny', Customer::class),
            'can_view_order' => Gate::allows('view-order'),
            // 'has_view_customer_permission' => $user->permissions()->contains('view-customer'), // Disabled: permissions system removed in #116
            // 'has_view_order_permission' => $user->permissions()->contains('view-order'), // Disabled: permissions system removed in #116
        ]);
    }
}