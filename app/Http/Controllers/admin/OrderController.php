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
        if ($request->ajax()) {
            $orders = Order::with(['customer', 'user'])
                ->notArchived()
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
                    $btn .= '<a href="'. route('admin.orders.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon mb-2" title="Ver"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="'. route('admin.orders.edit', $row->id) . '" class="btn btn-sm btn-success btn-action-icon mb-2" title="Editar"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning btn-action-icon mb-2" onclick="archiveOrder(' . $row->id . ')" title="Archivar"><i class="fas fa-archive"></i></button>';
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
    {        $order->load(['customer', 'orderProducts.product', 'payments']);
        
        return view('dashboard.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {        
        if ($order->archived) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'No se pueden editar órdenes archivadas. Solo puedes verlas.');
        }
        
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
        if ($order->archived) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'No se pueden actualizar órdenes archivadas.');
        }
        
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses())),
            'shipping_agency' => 'nullable|in:' . implode(',', Order::getShippingAgencies()),
            'shipping_tracking_number' => 'nullable|string|max:255',
            'exchange_rate' => 'nullable|numeric|min:0|required_if:status,pagada',
        ]);

        $updateData = $request->only([
            'status',
            'shipping_agency', 
            'shipping_tracking_number'
        ]);

        // Only set exchange_rate if the order is being marked as paid
        if ($request->status === 'pagada' && $request->filled('exchange_rate')) {
            $updateData['exchange_rate'] = $request->exchange_rate;
        }

        $oldStatus = $order->status;
        $order->update($updateData);

        // Send shipping notification if status changed to 'enviada'
        if ($oldStatus !== Order::STATUS_SHIPPED && $request->status === Order::STATUS_SHIPPED) {
            app(\App\Services\NotificationService::class)->sendShippingNotification($order);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Orden actualizada exitosamente.');
    }

    /**
     * Update order status via AJAX.
     */
    public function updateStatus(Request $request, Order $order)
    {        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses()))
        ]);

        $order->updateStatus($request->status);

        return response()->json([
            'success' => true,
            'message' => 'Estado de orden actualizado exitosamente.',
            'status' => $order->status_label
        ]);
    }

    /**
     * Cancel an order (for customers).
     */
    public function cancel(Order $order)
    {
        // Permitir que cualquier usuario cancele la orden (sin verificación de permisos)
        if ($order->status !== Order::STATUS_CREATED) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden cancelar órdenes en estado "Creada".'
            ], 400);
        }

        if ($order->cancel()) {
            return response()->json([
                'success' => true,
                'message' => 'Orden cancelada exitosamente.',
                'status' => $order->status_label
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudo cancelar la orden.'
        ], 400);
    }

    /**
     * Display archived orders.
     */
    public function archived(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::with(['customer', 'user'])
                ->archived()
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
                    $btn .= '<a href="'. route('admin.orders.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon mb-2" title="Ver"><i class="fas fa-eye"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-info btn-action-icon mb-2" onclick="unarchiveOrder(' . $row->id . ')" title="Desarchivar"><i class="fas fa-box-open"></i></button>';
                    return $btn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->toJson();
        }

        return view('dashboard.orders.archived');
    }

    /**
     * Archive an order.
     */
    public function archive(Order $order)
    {


        if ($order->archived) {
            return response()->json([
                'success' => false,
                'message' => 'La orden ya está archivada.'
            ], 400);
        }

        $order->archive();

        return response()->json([
            'success' => true,
            'message' => 'Orden archivada exitosamente.'
        ]);
    }

    /**
     * Unarchive an order.
     */
    public function unarchive(Order $order)
    {


        if (!$order->archived) {
            return response()->json([
                'success' => false,
                'message' => 'La orden no está archivada.'
            ], 400);
        }

        $order->unarchive();

        return response()->json([
            'success' => true,
            'message' => 'Orden desarchivada exitosamente.'
        ]);
    }
}