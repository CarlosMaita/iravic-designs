<?php

namespace App\Services\Excels;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InventoryExcelService 
{

    /**
     * Genera el archivo excel de inventario
     * 
     * @param array $data
     *  
     * 
     * */
	protected $path;
    
    private $regular_products;
    private $no_regular_products;
    private $stores;
    public $filename;

    public $excel;

    public function __construct($regular_products, $no_regular_products, $stores){ 
        $this->regular_products = $regular_products;
        $this->no_regular_products = $no_regular_products;
        $this->stores = $stores;
        $this->filename = $this->getFileName();
    }

    public function getFileName(){
        return 'Inventario';
    }

    public function generate(){
        $spreadsheet = $this->createSpreadsheet();
        $this->createProductsRegularesSheet($spreadsheet);
        $this->createProductsNoRegularesSheet($spreadsheet);
        $this->saveSpreadsheet($spreadsheet);
	}
    
    private function createSpreadsheet() : Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Brocsotf')
            ->setLastModifiedBy('Brocsotf')
            ->setTitle('Invetario de productos')
            ->setSubject('Invetario de productos')
            ->setDescription('Listado de inventario de productos');
        return $spreadsheet;
    }
    private function saveSpreadsheet($spreadsheet) : void
    {
        $this->path = storage_path($this->filename .'.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($this->path);
   }

    private function createProductsRegularesSheet($spreadsheet) : void
    {    
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Regulares');
        // create header
		$this->createProductsRegularesHeader($sheet);
		// create rows
		// $this->createProductsRegularesRows($sheet);
		// Apply styles
		$this->applyProductsRegularesStyles($sheet);
		// set protection
		// $this->setProtectionProductsRegulares($sheet);
      
    }

    public function createProductsRegularesHeader($sheet){
        $header = array();
        array_push($header, 'Id');
        array_push($header, 'brand_id');
        array_push($header, 'category_id');
        array_push($header, 'name');
        array_push($header, 'code');
        array_push($header, 'gender');
        array_push($header, 'price');
        // column for store
        foreach ($this->stores as $store) 
            array_push($header, 'stock_'.$store->name);

		$sheet->fromArray([$header], NULL, 'A1');
    }

    private function createProductsRegularesRows($sheet){
        $rows = array();
        foreach ($this->regular_products as $product) {
            // row for product
            $productRow = [
                'id' => $product->id,
                'brand_id' => $product->brand_id,
                'category_id' => $product->category_id,
                'name' => $product->name,
                'code' => $product->code,
                'gender' => $product->gender,
                'price' => $product->price ?? "0",
            ];
            // column for store
            foreach ($this->stores as $store) {
                $productRow['stock_' . $store->name] = $product->stores()->find($store->id)->pivot->stock ?? "0";
            }

            $rows[] = $productRow;
        }
        
        $sheet->fromArray($rows, NULL, 'A2');
    }
    

    private function applyProductsRegularesStyles($sheet){
        //acho de columnas
        $sheet->getColumnDimension('A')->setWidth(5); 
        $sheet->getColumnDimension('D')->setWidth(50); 
        $sheet->getColumnDimension('H')->setWidth(11); 
        $sheet->getColumnDimension('I')->setWidth(10); 
        $sheet->getColumnDimension('J')->setWidth(10);

        // titles style con bold y fondo gris
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'D3D3D3'],
            ],
        ]);

    }

    public function createProductsNoRegularesSheet( $spreadsheet){
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('No regulares');
        // create header
		$this->createProductsNoRegularesHeader($sheet);
		// create rows
		// $this->createProductsNoRegularesRows($sheet);
		// Apply styles
		$this->applyProductsNoRegularesStyles($sheet);
		// set protection
		// $this->setProtectionProductsNoRegulares($sheet);
    }

    public function createProductsNoRegularesHeader($sheet){
        $header = array();
        array_push($header, 'Id');
        array_push($header, 'brand_id');
        array_push($header, 'category_id');
        array_push($header, 'name');
        array_push($header, 'code');
        array_push($header, 'gender');
        array_push($header, 'color_id');
        array_push($header, 'text_color');
        array_push($header, 'product_id');
        array_push($header, 'size_id');
        array_push($header, 'price');
         // column for store
         foreach ($this->stores as $store) 
         array_push($header, 'stock_'.$store->name);

		$sheet->fromArray([$header], NULL, 'A1');
    }

    private function createProductsNoRegularesRows($sheet){
        $rows = array();
        foreach ($this->no_regular_products as $product) {
            // row for product
            $productRow[] = [
                'id' => $product->id,
                'brand_id' => $product->brand_id,
                'category_id' => $product->category_id,
                'name' => $product->name,
                'code' => $product->code,
                'gender' => $product->gender,
                'color_id' => $product->color_id,
                'text_color' => $product->text_color,
                'product_id' => $product->product_id,
                'size_id' => $product->size_id,
                'price' => $product->price ?? "0",
            ];
            // column for store
            foreach ($this->stores as $store) {
                $productRow['stock_' . $store->name] = $product->stores()->find($store->id)->pivot->stock ?? "0";
            }

            $rows[] = $productRow;
            
        }
        // agregar fondo gris a las celdas donde el product_id es nulo
        

        $sheet->fromArray($rows, NULL, 'A2');
    }

    public function applyProductsNoRegularesStyles ($sheet){
        //acho de columnas
        $sheet->getColumnDimension('A')->setWidth(5); 
        $sheet->getColumnDimension('D')->setWidth(50); 
        $sheet->getColumnDimension('F')->setWidth(15); 
        $sheet->getColumnDimension('I')->setWidth(10); 
        $sheet->getColumnDimension('L')->setWidth(11); 
        $sheet->getColumnDimension('M')->setWidth(10); 
        $sheet->getColumnDimension('N')->setWidth(10);  

         // titles style con bold y fondo gris
         $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'D3D3D3'],
            ],
        ]);


        // agregar fondo gris a las filas donde el product_id es nulo
        $row = 2;
        foreach ($this->no_regular_products as $product) {
            if ($product->product_id == null) {
                $sheet->getStyle('A'.($row) .':N'.($row))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D3D3D3'], 
                    ],
                ]);
            }
            $row++;
        }

       

      
    }

	public function getPath() {
		return $this->path;
	}

   
}

?>