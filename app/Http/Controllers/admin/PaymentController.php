<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $payments = Payment::with(['order', 'customer'])
                ->orderBy('created_at', 'desc');

            // Filter by status if provided (non-empty)
            if ($request->filled('status')) {
                $payments->where('status', $request->status);
            }

            return DataTables::of($payments)
                ->addIndexColumn()
                ->addColumn('customer_name', function($row) {
                    return $row->customer ? $row->customer->name : 'N/A';
                })
                ->addColumn('order_number', function($row) {
                    return $row->order ? '#' . $row->order->id : 'N/A';
                })
                ->addColumn('status_badge', function($row) {
                    $statusColors = [
                        'pendiente' => 'warning',
                        'verificado' => 'success', 
                        'rechazado' => 'danger'
                    ];
                    $color = $statusColors[$row->status] ?? 'secondary';
                    return '<span class="badge badge-' . $color . '">' . $row->status_label . '</span>';
                })
                ->addColumn('amount_formatted', function($row) {
                    return '$' . number_format($row->amount, 2);
                })
                ->addColumn('date_formatted', function($row) {
                    return $row->date->format('d/m/Y H:i');
                })
                ->addColumn('reference_formatted', function($row) {
                    return $row->reference_digits ? $row->reference_digits . '...' : 'N/A';
                })
                ->addColumn('action', function($row) {
                    $btn = '';
                    $btn .= '<a href="'. route('admin.payments.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon mb-2" title="Ver"><i class="fas fa-eye"></i></a>';
                    if ($row->status === 'pendiente') {
                        $btn .= '<button onclick="verifyPayment(' . $row->id . ')" class="btn btn-sm btn-success btn-action-icon mb-2" title="Verificar"><i class="fas fa-check"></i></button>';
                        $btn .= '<button onclick="rejectPayment(' . $row->id . ')" class="btn btn-sm btn-danger btn-action-icon mb-2" title="Rechazar"><i class="fas fa-times"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->toJson();
        }

        $statuses = Payment::getStatuses();
        return view('dashboard.payments.index', compact('statuses'));
    }

    /**
     * Show the specified payment.
     */
    public function show(Payment $payment)
    {
        
        $payment->load(['order', 'customer']);
        
        return view('dashboard.payments.show', compact('payment'));
    }

    /**
     * Verify a payment.
     */
    public function verify(Payment $payment)
    {

        if ($payment->verify()) {
            return response()->json([
                'success' => true,
                'message' => 'Pago verificado exitosamente.',
                'status' => $payment->status_label
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudo verificar el pago.'
        ]);
    }

    /**
     * Reject a payment.
     */
    public function reject(Payment $payment)
    {

        if ($payment->reject()) {
            return response()->json([
                'success' => true,
                'message' => 'Pago rechazado exitosamente.',
                'status' => $payment->status_label
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudo rechazar el pago.'
        ]);
    }

    /**
     * Update payment status.
     */
    public function updateStatus(Request $request, Payment $payment)
    {

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Payment::getStatuses())),
            'comment' => 'nullable|string|max:1000',
            'currency' => 'nullable|in:' . implode(',', array_keys(Payment::getCurrencies())),
            'exchange_rate' => 'nullable|numeric|min:0.0001',
            'local_amount' => 'nullable|numeric|min:0.01'
        ]);

        $updateData = [
            'status' => $request->status,
            'comment' => $request->comment
        ];

        // Handle currency fields if provided
        if ($request->has('currency')) {
            $updateData['currency'] = $request->currency;
        }
        if ($request->has('exchange_rate')) {
            $updateData['exchange_rate'] = $request->exchange_rate;
        }
        if ($request->has('local_amount')) {
            $updateData['local_amount'] = $request->local_amount;
        }

        $payment->update($updateData);

        // If payment is verified and order is fully paid, update order status
        if ($request->status === 'verificado' && 
            $payment->order && 
            $payment->order->is_fully_paid && 
            $payment->order->status === 'creada') {
            $payment->order->updateStatus('pagada');
        }

        return response()->json([
            'success' => true,
            'message' => 'Estado de pago actualizado exitosamente.',
            'status' => $payment->status_label
        ]);
    }

    /**
     * Store a new payment with multi-currency support.
     */
    public function store(Request $request)
    {

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|in:' . implode(',', array_keys(Payment::getCurrencies())),
            'exchange_rate' => 'required|numeric|min:0.0001',
            'local_amount' => 'nullable|numeric|min:0.01',
            'payment_method' => 'required|in:' . implode(',', array_keys(Payment::getPaymentMethods())),
            'reference_number' => 'nullable|string|max:255',
            'mobile_payment_date' => 'nullable|date',
            'comment' => 'nullable|string|max:1000'
        ]);

        $order = Order::findOrFail($request->order_id);

        $payment = Payment::create([
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'user_id' => Auth::id(),
            'date' => now(),
            'amount' => $request->amount,
            'currency' => $request->currency,
            'exchange_rate' => $request->exchange_rate,
            'local_amount' => $request->local_amount,
            'status' => Payment::STATUS_PENDING,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'mobile_payment_date' => $request->mobile_payment_date,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago registrado exitosamente.',
            'payment' => $payment->load(['order', 'customer'])
        ]);
    }
}