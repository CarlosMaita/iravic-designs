<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display customer payments.
     */
    public function index()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login.form');
        }

        $customer = Auth::guard('customer')->user();
        $payments = $customer->payments()
            ->with(['order'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Add categories for header navigation (empty collection to prevent errors)
        $categories = collect();

        return view('ecommerce.payments.index', compact('payments', 'categories'));
    }
}