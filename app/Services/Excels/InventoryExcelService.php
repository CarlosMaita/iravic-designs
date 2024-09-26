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
    
    public $products;
    public $filename;

    public $excel;

    public function __construct($products) {
        $this->products = $products;
        $this->filename = $this->getFileName();
    }
    
    public function getFileName(){
        return 'Inventario'.date('Y-m-d');
    }

    public function generate(){
        $spreadsheet = $this->createSpreadsheet();
        $this->createProductsRegularesSheet($spreadsheet);
        // $this->createProductsNoRegularesSheet($spreadsheet);
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
        $sheet->setCellValue('A1', 'No'); // This is where you set the column header
        $sheet->setCellValue('B1', 'Name');// This is where you set the column header
        // create header
		// $this->createProductsRegularesHeader($sheet);
		// create rows
		// $this->createProductsRegularesRows($sheet);
		// Apply styles
		// $this->applyProductsRegularesStyles($sheet);
		// set protection
		// $this->setProtectionProductsRegulares($sheet);
      
    }

    public function createProductsRegularesHeader($sheet){
        $header = array();
        array_push($header, 'Id');
        array_push($header, 'Nombre');
        array_push($header, 'Apellido');
        array_push($header, 'Salario con Incidencias');
        array_push($header, 'Hora a trabajar');

		$sheet->fromArray([$header], NULL, 'A1');
    }
    
    public function createProductsNoRegularesSheet( $spreadsheet){
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('No regulares');

        // create header
		// $this->createProductsNoRegularesHeader($sheet);
		// create rows
		// $this->createProductsNoRegularesRows($sheet);
		// Apply styles
		// $this->applyProductsNoRegularesStyles($sheet);
		// set protection
		// $this->setProtectionProductsNoRegulares($sheet);
    }

    public function createProductsNoRegularesHeader($sheet){
        $header = array();
        array_push($header, 'Id');
        array_push($header, 'Nombre');
        array_push($header, 'Apellido');
        array_push($header, 'Salario con Incidencias');
        array_push($header, 'Hora a trabajar');

		$sheet->fromArray([$header], NULL, 'A1');
    }

	public function getPath() {
		return $this->path;
	}

   
}

?>