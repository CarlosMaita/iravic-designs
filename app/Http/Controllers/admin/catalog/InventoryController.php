<?php

namespace App\Http\Controllers\admin\catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Excels\InventoryExcelService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $products = $this->productRepository->onlyPrincipalsQuery($criteria);
            $products = Product::all();
            return datatables()->eloquent($products)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<div style="display:flex">';

                if (Auth::user()->can('view', $row)) {
                    $btn .= '<a href="'. route('productos.show', $row->id) . '" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>';
                }

                return $btn;
            })
            ->make(true);
        }
        return view('dashboard.catalog.inventory.index');
    }

    public function download(){
        
        $products = Product::all();
		$inventory_excel = new InventoryExcelService($products);
		$inventory_excel->generate();

		// Obtén la ruta del archivo
		$path = $inventory_excel->getPath();
		// Descarga el archivo
		return response()->download($path)
            ->deleteFileAfterSend(true);
    }

    public function upload(Request $request){

    }
}


