<?php

namespace App\Http\Controllers\ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyprofileEcommerceController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer = Auth::user();
        if($request->ajax()){
            return response()->json($customer);
        }
        
        $orders = $customer->orders()->orderBy('date', 'desc')->get();
        $refunds = $customer->refunds()->orderBy('date', 'desc')->get();
        $showOrdersTab = isset($request->pedidos) ? true : false;
        $showRefundsTab = isset($request->devoluciones) ? true : false;
        $planningCollection = $customer->getPlanningCollection();
        
        return view('ecommerce.my-profile.index', [
            'customer' => $customer, 
            'orders' => $orders,
            'refunds' => $refunds, 
            'showOrdersTab' => $showOrdersTab, 
            'showRefundsTab' => $showRefundsTab,
            'planningCollection' => $planningCollection
        ]);
    }
    
}
