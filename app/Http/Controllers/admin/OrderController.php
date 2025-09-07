<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $this->authorize('view-order');

        if ($request->ajax()) {
            $orders = Order::with(['customer', 'user'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('customer_name', function($row) {
                    return $row->customer ? $row->customer->name : 'N/A';
                })
                ->addColumn('status_badge', function($row) {
                    $statusColors = [
                        'creada' => 'secondary',
                        'pagada' => 'info', 
                        'enviada' => 'warning',
                        'completada' => 'success',
                        'cancelada' => 'danger'
                    ];
                    $color = $statusColors[$row->status] ?? 'secondary';
                    return '<span class="badge badge-' . $color . '">' . $row->status_label . '</span>';
                })
                ->addColumn('total_formatted', function($row) {
                    return '$' . number_format($row->total, 2);
                })
                ->addColumn('date_formatted', function($row) {
                    return $row->date->format('d/m/Y H:i');
                })
                ->addColumn('action', function($row) {
                    $btn = '';
                    if (Auth::user()->can('view-order')) {
                        $btn .= '<a href="'. route('admin.orders.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon mb-2" title="Ver"><i class="fas fa-eye"></i></a>';
                    }
                    if (Auth::user()->can('update-order')) {
                        $btn .= '<a href="'. route('admin.orders.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon mb-2" title="Editar"><i class="fas fa-edit"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->toJson();
        }

        return view('dashboard.orders.index');
    }

    /**
     * Show the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view-order');
        
        $order->load(['customer', 'orderProducts.product', 'payments']);
        
        return view('dashboard.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $this->authorize('update-order');
        
        $order->load(['customer', 'orderProducts', 'payments']);
        $statuses = Order::getStatuses();
        $shippingAgencies = Order::getShippingAgencies();
        
        return view('dashboard.orders.edit', compact('order', 'statuses', 'shippingAgencies'));
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update-order');

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses())),
            'shipping_agency' => 'nullable|in:' . implode(',', Order::getShippingAgencies()),
            'shipping_tracking_number' => 'nullable|string|max:255',
        ]);

        $order->update($request->only([
            'status',
            'shipping_agency', 
            'shipping_tracking_number'
        ]));

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Orden actualizada exitosamente.');
    }

    /**
     * Update order status via AJAX.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update-order');

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses()))
        ]);

        $order->updateStatus($request->status);

        return response()->json([
            'success' => true,
            'message' => 'Estado de orden actualizado exitosamente.',
            'status' => $order->status_label
        ]);
    }
}