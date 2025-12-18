<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Show the customer dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        // Get customer statistics (only count non-archived orders)
        $ordersCount = $customer->orders()->notArchived()->count();
        
        // Get recent notifications (last 5)
        $recentNotifications = $customer->notifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('ecommerce.dashboard.index', compact('customer', 'ordersCount', 'recentNotifications'));
    }

    /**
     * Show the customer profile.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        
        return view('ecommerce.dashboard.profile', compact('customer'));
    }

    /**
     * Return the authenticated customer's shipping information as JSON.
     */
    public function getShippingJson(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
        }

        $shipping = [
            'name' => $customer->name ?? '',
            'dni' => $customer->dni ?? '',
            'phone' => $customer->cellphone ?? '',
            'agency' => $customer->shipping_agency ?? 'MRW',
            'address' => $customer->shipping_agency_address ?? '',
        ];

        return response()->json([
            'success' => true,
            'shipping' => $shipping,
            'has_complete' => $customer->hasCompleteShippingInfo(),
        ]);
    }

    /**
     * Update the authenticated customer's shipping information.
     */
    public function updateShipping(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('customer.login.form');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'agency' => 'required|in:MRW,ZOOM,Domesa',
            'address' => 'required|string|max:500',
        ]);

        $customer->update([
            'name' => $validated['name'],
            'dni' => $validated['dni'],
            'cellphone' => $validated['phone'],
            'shipping_agency' => $validated['agency'],
            'shipping_agency_address' => $validated['address'],
        ]);

        return redirect()
            ->route('customer.profile')
            ->with('status', 'Información de envío actualizada correctamente.');
    }
}