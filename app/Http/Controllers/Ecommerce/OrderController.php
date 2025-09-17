<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Create a new order from cart data.
     */
    public function create(Request $request)
    {
        // Validate request
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.color_id' => 'nullable|exists:colors,id',
            'items.*.size_id' => 'nullable|exists:sizes,id',
            'shipping_data' => 'required|array',
            'shipping_data.name' => 'required|string|max:255',
            'shipping_data.dni' => 'required|string|max:20',
            'shipping_data.phone' => 'required|string|max:20',
            'shipping_data.agency' => 'required|in:' . implode(',', Order::getShippingAgencies()),
            'shipping_data.address' => 'required|string|max:500',
        ]);

        // Check if customer is authenticated
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debe iniciar sesión para realizar una compra.',
                'redirect' => route('customer.login.form')
            ], 401);
        }

        $customer = Auth::guard('customer')->user();

    // No exigir que el cliente tenga información previa guardada.
    // Usaremos la información enviada en shipping_data y, además, la persistiremos en el perfil para futuras compras.

        try {
            DB::beginTransaction();

            // Update customer shipping information from the shipping data
            $customer->update([
                'name' => $request->shipping_data['name'],
                'dni' => $request->shipping_data['dni'],
                'cellphone' => $request->shipping_data['phone'],
                'shipping_agency' => $request->shipping_data['agency'],
                'shipping_agency_address' => $request->shipping_data['address'],
            ]);

            // Calculate totals
            $subtotal = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Producto no encontrado.');
                }

                $itemTotal = $product->price * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'qty' => $item['quantity'],
                    'total' => $itemTotal,
                    'color_id' => $item['color_id'] ?? null,
                    'size_id' => $item['size_id'] ?? null,
                ];
            }

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'date' => Carbon::now(),
                'total' => $subtotal,
                'subtotal' => $subtotal,
                'discount' => 0,
                'status' => Order::STATUS_CREATED,
                'shipping_name' => $request->shipping_data['name'],
                'shipping_dni' => $request->shipping_data['dni'],
                'shipping_phone' => $request->shipping_data['phone'],
                'shipping_agency' => $request->shipping_data['agency'],
                'shipping_address' => $request->shipping_data['address'],
            ]);

            // Create order products
            foreach ($orderItems as $orderItem) {
                $order->orderProducts()->create($orderItem);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orden creada exitosamente.',
                'order_id' => $order->id,
                'redirect' => route('customer.orders.show', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show customer orders.
     */
    public function index()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login.form');
        }

        $customer = Auth::guard('customer')->user();
        $orders = $customer->orders()
            ->notArchived()
            ->with(['orderProducts.product', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Add categories for header navigation (empty collection to prevent errors)
        $categories = collect();

        return view('ecommerce.orders.index', compact('orders', 'categories'));
    }

    /**
     * Show specific order for customer.
     */
    public function show(Order $order)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login.form');
        }

        $customer = Auth::guard('customer')->user();

        // Check if order belongs to customer
        if ($order->customer_id !== $customer->id) {
            abort(403, 'No autorizado para ver esta orden.');
        }

        $order->load(['orderProducts.product', 'payments']);

        // Add categories for header navigation (empty collection to prevent errors)
        $categories = collect();

        return view('ecommerce.orders.show', compact('order', 'categories'));
    }

    /**
     * Add payment to order.
     */
    public function addPayment(Request $request, Order $order)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 401);
        }

        $customer = Auth::guard('customer')->user();

        // Check if order belongs to customer
        if ($order->customer_id !== $customer->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:' . implode(',', array_keys(Payment::getPaymentMethods())),
            'reference_number' => 'required_if:payment_method,pago_movil|nullable|string|min:6|max:50',
            'mobile_payment_date' => 'required_if:payment_method,pago_movil|nullable|date',
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $payment = Payment::create([
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'date' => Carbon::now(),
                'amount' => $request->amount,
                'status' => Payment::STATUS_PENDING,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'mobile_payment_date' => $request->mobile_payment_date ? Carbon::parse($request->mobile_payment_date) : null,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado exitosamente. Será verificado por nuestro equipo.',
                'payment_id' => $payment->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order.
     */
    public function cancel(Request $request, Order $order)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 401);
        }

        $customer = Auth::guard('customer')->user();

        // Check if order belongs to customer
        if ($order->customer_id !== $customer->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        // Check if order can be cancelled
        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false, 
                'message' => 'Esta orden no puede ser cancelada. Solo las órdenes en estado "Creada" pueden ser canceladas.'
            ], 400);
        }

        try {
            $order->cancel();

            return response()->json([
                'success' => true,
                'message' => 'Orden cancelada exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la orden: ' . $e->getMessage()
            ], 500);
        }
    }
}