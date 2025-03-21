<?php

namespace App\Http\Controllers\admin\catalog;

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

        return response()
            ->download( $path, 'inventario.xlsx' ,
             [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="' . 'inventario.xlsx' . '"',
             ])
            ->deleteFileAfterSend(true);
    }


    public function upload(Request $request){
        $file = $request->file('file');
        $id_row = 0;

        try {
            // leer datos de excel
            $data_regular_products 		= IOFactory::load($file->getRealPath())->getSheet(0)->toArray();
            $data_no_regular_products 	= IOFactory::load($file->getRealPath())->getSheet(1)->toArray();

            // validar formato de archivo
            if (empty($data_regular_products[0][0]) == 'Id'  && empty($data_no_regular_products[0][0]) == 'Id') {
                return response()->json([
                    'status' => 420,
                    'success' => false,
                    'message' => 'El archivo no tiene el formato correcto'
                ]);
            }

            // Validar que existen datos en el archivo
            if (empty($data_regular_products[$id_row + 1][0]) && empty($data_no_regular_products[$id_row + 1][0])) {
                return response()->json([
                    'status' => 420,
                    'success' => false,
                    'message' => 'No se encontraron datos en el archivo'
                ]);
            }

           
            // rows para productos regulares
            $price_row = 6;
            $stock_depot_row = 7;
            $stock_local_row = 8;
            $stock_truck_row = 9;
            # Procesar productos Regulares
            foreach($data_regular_products as $row) {
                if(!empty($row[$id_row])){
                    if($row[$id_row] == "Id") continue;
                    $id =  $row[$id_row];

                    $product = Product::find($id);
                    if ($product){
                        $price = $row[$price_row] ? $row[$price_row] : 0;
                        $stock_depot = $row[$stock_depot_row] ? $row[$stock_depot_row] : 0;
                        $stock_local = $row[$stock_local_row] ? $row[$stock_local_row] : 0;
                        $stock_truck = $row[$stock_truck_row] ? $row[$stock_truck_row] : 0;
           

                        if ( (is_numeric($stock_depot) && is_numeric($stock_local)  && is_numeric($stock_truck) && is_numeric($price)) ){
                            $product->price = $price;
                            $product->stock_depot = $stock_depot;
                            $product->stock_local = $stock_local;
                            $product->stock_truck = $stock_truck;
                            $product->save();
                        }
                    }                  
                }else{
                    break;
                }
            }

            // rows para productos regulares
            $price_row = 10;
            $stock_depot_row = 11;
            $stock_local_row = 12;
            $stock_truck_row = 13;
            # Procesar productos Regulares
            foreach($data_no_regular_products as $row) {
                if(!empty($row[$id_row])){
                    if($row[$id_row] == "Id") continue;
                    $id =  $row[$id_row];

                    $product = Product::find($id);
                    if ($product){
                        $price = $row[$price_row] ? $row[$price_row] : 0;
                        $stock_depot = $row[$stock_depot_row] ? $row[$stock_depot_row] : 0;
                        $stock_local = $row[$stock_local_row] ? $row[$stock_local_row] : 0;
                        $stock_truck = $row[$stock_truck_row] ? $row[$stock_truck_row] : 0;

                        if ( (is_numeric($stock_depot) && is_numeric($stock_local)  && is_numeric($stock_truck) && is_numeric($price)) ){
                            $product->price = $price;
                            $product->stock_depot = $stock_depot;
                            $product->stock_local = $stock_local;
                            $product->stock_truck = $stock_truck;
                            $product->save();
                        }
                    }                  
                }else{
                    break;
                }
            }

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
}


