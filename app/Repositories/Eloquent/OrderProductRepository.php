<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderProduct;
use App\Repositories\OrderProductRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OrderProductRepository extends BaseRepository implements OrderProductRepositoryInterface
{

    /**
     * OrderProductRepository constructor.
     *
     * @param OrderProduct $model
     */
    public function __construct(OrderProduct $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de productos que estan disponibles para ser devueltos por un cliente
     * Solo son considerados productos que hayan sido comprado por el cliente, y que la cantidad que haya devuelto del mismo producto sea menor a la comprada
     * 
     * @return collection
     */
    public function availableForRefund($customer_id): Collection
    {
        $products = new Collection;
        #nota old query usado para conseguir las ordenes de producto, estaba generando error en local. 
        // $orders_products = $this->model->with('color', 'product', 'size')
        //                     ->whereHas('order', function ($q) use ($customer_id) {
        //                         $q->whereDoesntHave('refund', function($q) use ($customer_id) {
        //                             $q->where('customer_id', '<>', $customer_id);
        //                         })
        //                         ->where('customer_id', $customer_id);
        //                     })
        //                     ->where(function($q) {
        //                         $q->whereHas('refunds_products', function ($query){
        //                             $query->havingRaw('orders_products.qty>sum(qty)');
        //                             $query->groupBy('order_product_id');
        //                         })
        //                         ->orWhereDoesntHave('refunds_products');
        //                     })
        //                     ->get();

        $orders_products = $this->model->with('color', 'product', 'size')
                        ->whereHas('order', function ($q) use ($customer_id) {
                            $q->whereDoesntHave('refund', function ($q) use ($customer_id) {
                                $q->where('customer_id', '<>', $customer_id);
                            })
                            ->where('customer_id', $customer_id);
                        })
                        ->where(function ($q) {
                            $q->whereExists(function ($query) {
                                $query->select('order_product_id')
                                    ->from('refunds_products')
                                    ->whereColumn('orders_products.id', 'refunds_products.order_product_id')
                                    ->groupBy('order_product_id')
                                    ->havingRaw('orders_products.qty > SUM(refunds_products.qty)');
                            })
                            ->orWhereDoesntHave('refunds_products');
                        })
                        ->get();

        foreach ($orders_products as $order_product) {
            $product = $order_product->product;

            if ($parent_product = $product->parent) {
                $product = $parent_product;
            }

            $index = $products->search(function($p) use ($product) {
                return $p->id === $product->id;
            });

            if ($index === false) {
                $product_to_add = new \stdClass();
                $product_to_add->id = $product->id;
                $product_to_add->name = $product->name;
                $product_to_add->code = $product->code;
                $product_to_add->brand_name = optional($product->brand)->name;
                $product_to_add->category_name = optional($product->category)->name;
                $product_to_add->order_products = array($order_product);
                $products->push($product_to_add);
            } else {
                array_push($products[$index]->order_products, $order_product);
            }
        }

        return $products;
    }
}