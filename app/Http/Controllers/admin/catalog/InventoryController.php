<?php

namespace App\Http\Controllers\admin\catalog;

use App\Events\ProductStockChanged;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Services\Excels\InventoryExcelService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InventoryController extends Controller
{

    public function download()
    {
        $stores = Store::all();
        $regularProducts = Product::where('is_regular' , 1)->get();
        $noRegularProducts = Product::where('is_regular' , 0)->orderBy('created_at', 'desc')->get();
        
        // generar excel
        $inventoryExcel = new InventoryExcelService($regularProducts, $noRegularProducts, $stores);
        $inventoryExcel->generate();
        $path = $inventoryExcel->getPath();

        $now = now();
        $filename = "inventario-{$now->format('Ymd-His')}.xlsx";

        return response()
            ->download( $path, $filename ,
             [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment;filename=\"{$filename}\"",
             ])
            ->deleteFileAfterSend(true);
    }


    public function upload(Request $request){
    ini_set('max_execution_time', 300);

    try {
        $id_row = 0;
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $data_regular_products = $spreadsheet->getSheet(0)->toArray();
        $data_no_regular_products = $spreadsheet->getSheet(1)->toArray();

        $this->validateTemplateAndData($data_regular_products, $data_no_regular_products, $id_row);

        $stores = Store::all();
        $storeIds = $stores->pluck('id')->toArray();

        $this->processProducts($data_regular_products, $id_row, 6, $storeIds);
        $this->processProducts($data_no_regular_products, $id_row, 10, $storeIds, 13);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Se han actualizado los inventarios'
        ]);

    } catch (\Throwable $th) {
        return response()->json([
            'status' => 420,
            'success' => false,
            'message' => 'No se pudo leer el archivo'
        ]);
    }
}

private function validateTemplateAndData($data_regular_products, $data_no_regular_products, $id_row){
    if (empty($data_regular_products) || empty($data_no_regular_products) || $data_regular_products[0][0] !== 'Id' || $data_no_regular_products[0][0] !== 'Id' || empty($data_regular_products[$id_row + 1][0]) || empty($data_no_regular_products[$id_row + 1][0])) {
        return response()->json([
            'status' => 420,
            'success' => false,
            'message' => 'El archivo no tiene el formato correcto o no se encontraron datos en el archivo'
        ]);
    }
}

private function processProducts($data_products, $id_row, $price_row, $storeIds, $stock_row_start = 9)
{
    $ids = array_unique(array_filter(array_column($data_products, $id_row)));
    $products = Product::whereIn('id', $ids)->get();

    foreach ($data_products as $row) {
        if (empty($row[$id_row])) break;

        $id = $row[$id_row];
        $product = $products->firstWhere('id', $id);
        if ($product) {
            $price = $row[$price_row] ?? 0;
            $product->price = $price;
            $product->save();

            foreach ($storeIds as $index => $store_id) {
                $new_stock = $row[$stock_row_start + $index] ?? 0;
                $current_stock = $product->stores()->find($store_id)->pivot->stock ?? 0;
                $stock_difference = $new_stock - $current_stock;

                if ($stock_difference !== 0) {
                     // Verificar si el vínculo ya existe
                    if ($product->stores()->where('store_id', $store_id)->exists()) {
                        $product->stores()->updateExistingPivot($store_id, ['stock' => $new_stock]);
                    }else{
                        $product->stores()->attach($store_id, ['stock' => $new_stock]);
                    }
                    event(new ProductStockChanged($product->id, $store_id, $current_stock, $new_stock, $stock_difference, 'Asignacion Masiva de stock', auth()->id()));
                }
            }
        }
    }
}

   
}


