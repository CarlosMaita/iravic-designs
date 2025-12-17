<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::ordered()->get();
        return view('dashboard.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.payment-methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:payment_methods,code',
            'instructions' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        PaymentMethod::create([
            'name' => $request->name,
            'code' => $request->code,
            'instructions' => $request->instructions,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Método de pago creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('dashboard.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:payment_methods,code,' . $paymentMethod->id,
            'instructions' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $paymentMethod->update([
            'name' => $request->name,
            'code' => $request->code,
            'instructions' => $request->instructions,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Método de pago actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Check if payment method is being used
        if ($paymentMethod->payments()->count() > 0) {
            return redirect()->route('admin.payment-methods.index')
                ->with('error', 'No se puede eliminar este método de pago porque tiene pagos asociados.');
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Método de pago eliminado exitosamente.');
    }

    /**
     * Toggle active status of a payment method.
     */
    public function toggleActive(PaymentMethod $paymentMethod)
    {
        $paymentMethod->is_active = !$paymentMethod->is_active;
        $paymentMethod->save();

        return response()->json([
            'success' => true,
            'is_active' => $paymentMethod->is_active,
            'message' => $paymentMethod->is_active ? 
                'Método de pago activado.' : 
                'Método de pago desactivado.'
        ]);
    }
}
