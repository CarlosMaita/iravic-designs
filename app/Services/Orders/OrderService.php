<?php

namespace App\Services\Orders;

class OrderService
{
    /**
     * Retorna array con valores de una devolucion
     * 
     * @return array
     */
    public static function getOrderTotalsByRefund($params, $productRepository, $orderProductRepository)
    {
        $discount = isset($params['discount']) && is_numeric($params['discount']) ? (int) $params['discount'] : 0;
        $subtotal = self::getSubtotalOrder($productRepository, $params);
        // $subtotal = self::getSubtotalOrder($productRepository, $params['products'], $params['qtys']);
        $payment_method = isset($params['payment_method']) ? $params['payment_method'] : null;
        $totalCancel = 0;
        $totalsRefund = self::getTotalsToRefund($orderProductRepository, $params['products_refund'], $params['qtys_refund']);
        $totalRefund = $totalsRefund['total'];
        $totalRefundDebit = $totalsRefund['total_by_debit'];
        $totalRefundCredit = $totalsRefund['total_by_credit'];
        $totalOrder = $subtotal - $discount;

        if ($payment_method == 'credit' && $totalOrder > $totalRefundCredit && isset($params['is_credit_shared'])) {
            $totalCancel = $totalOrder - $totalRefundDebit - $totalRefundCredit;
        } else {
            $totalCancel = $totalOrder;
        }

        return [
            'discount' => $discount,
            'subtotal' => $subtotal,
            'total_order' => $totalOrder,
            'total_cancel' => $totalCancel,
            'total_refund' => $totalRefund,
            'total_refund_debit' => $totalRefundDebit,
            'total_refund_credit' => $totalRefundCredit
        ];
    }

    /**
     * Retorna subtotal de una venta
     */
    public static function getSubtotalOrder($productRepository, $params)
    {
        $products = !empty($params['products']) ? $params['products'] : [];
        $qtys = !empty($params['qtys']) ? $params['qtys'] : [];
        $subtotal = 0;

        foreach ($products as $keyProduct => $stores) {
            if ($product = $productRepository->find($keyProduct)) {
                if (isset($qtys[$keyProduct])) {
                    foreach ($qtys[$keyProduct] as $keyStore => $qty) {
                        if ($qty <= 0) { continue; }
                        $real_price =  $product->regular_price; // Precio regular por defecto
                        $subtotal += ($real_price * $qty);
                    }
                }
            }   
        }
        return $subtotal;
    }

    /**
     * Retorna totales de devolucion. Dividido en total por credito y debito y suma de ambos
     */
    public static function getTotalsToRefund($orderProductRepository, $products, $qtys)
    {
        $total_by_credit = 0;
        $total_by_debit = 0;

        foreach ($products as $keyProduct => $stores) {
            if ($product = $orderProductRepository->find($keyProduct)) {
                if (isset($qtys[$keyProduct])) {
                    foreach ($qtys[$keyProduct] as $keyStore => $qty) {
                        if ($qty <= 0) { continue; }
                        $total_product = ($product->product_price * $qty);
                        if ($product->order->payed_credit == 1) {
                            $total_by_credit += $total_product;
                        } else {
                            $total_by_debit += $total_product;
                        }
                    }
                }
            }
        }

        return [
            'total' => ($total_by_credit + $total_by_debit),
            'total_by_credit' => $total_by_credit,
            'total_by_debit' => $total_by_debit
        ];
    }
}