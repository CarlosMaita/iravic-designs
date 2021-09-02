<?php

namespace App\Services\Catalog;

class StockService
{
    public static function getStockName($stock)
    {
        $stock_name = '';

        switch ($stock) {
            case 'stock_depot':
                $stock_name = 'Depósito';
                break;

            case 'stock_local':
                $stock_name = 'Local';
                break;

            case 'stock_truck':
                $stock_name = 'Camión';
                break;
            
            default:
                break;
        }

        return $stock_name;
    }
}