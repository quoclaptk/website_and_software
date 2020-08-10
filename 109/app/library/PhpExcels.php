<?php namespace Modules;

require_once __DIR__.'/../../vender/PHPExcel/PHPExcel.php';

class PhpExcels
{
    public $inputFileType = 'Excel2007';
    /*$inputFileType = 'Excel5';
    $inputFileType = 'Excel2003XML';
    $inputFileType = 'OOCalc';
    $inputFileType = 'Gnumeric';*/
    
    public static function checkNamHoc($NamHoc)
    {
        if (!preg_match('/^[0-9]{4}-[0-9]{4}$/', $NamHoc)) {
            return false;
        }
        return true;
    }
    public static function checkMaSV($MaSV)
    {
        if (!is_numeric($MaSV)) {
            return false;
        }
        return true;
    }
    public static function checkString($Str)
    {
        if ($Str=='') {
            return false;
        }
        return true;
    }
    public static function checkMaHocPhan($MaHocPhan)
    {
        if (!is_numeric($MaHocPhan)) {
            return false;
        }
        return true;
    }
    public static function _checkDiemThi($Diem)
    {
        if (!is_numeric($Diem)) {
            return false;
        }
        if (floatval($Diem)<0 || floatval($Diem)>10) {
            return false;
        }
        return true;
    }
    public static function errorHightLight()
    {
        return array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF0000')
            )
        );
    }
}
    //$sheetNames = $objPHPExcel->getSheetNames();
                //echo $objPHPExcel->getSheetCount();
                /*foreach($sheetNames as $sheetIndex => $sheetName) {
                    $rowIterator = $objPHPExcel->getSheet($sheetIndex)->getRowIterator();

                   foreach($rowIterator as $row){
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                        $rowIndex = $row->getRowIndex ();

                        if(1== $rowIndex) continue;//skip first row

                        foreach ($cellIterator as $key => $value) {
                                $data[$rowIndex][$key] = $value->getCalculatedValue();
                        }

                    }//end foreach rowIterator
                  echo "<pre>";
                 print_r($data);
                }//end sheetNames*/
      /* $objPHPExcel->getActiveSheet()->setAutoFilter('A1:E20');
                    $autoFilter = $objPHPExcel->getActiveSheet()->getAutoFilter();
                    $columnFilter = $autoFilter->getColumn('C');
                    $columnFilter->setFilterType(
                         \PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_FILTERTYPE_FILTER
                    );
                    $columnFilter->createRule()
                    ->setRule(
                            \PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_EQUAL,
                        'France'
                    );
                    $columnFilter->createRule()
                        ->setRule(
                            \PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_EQUAL,
                            'Germany'
                    );
                        $autoFilter = $objPHPExcel->getActiveSheet()->getAutoFilter();
                    $autoFilter->showHideRows();
                    foreach ($objPHPExcel->getActiveSheet()->getRowIterator() as $row) {
                        if ($objPHPExcel->getActiveSheet()->getRowDimension($row->getRowIndex())->getVisible()) {
                            echo '    Row number - ' , $row->getRowIndex() , ' ';
                            echo $objPHPExcel->getActiveSheet()->getCell(
                                'C'.$row->getRowIndex()
                            )->getValue(), ' ';
                            echo $objPHPExcel->getActiveSheet()->getCell(
                                'D'.$row->getRowIndex()
                            )->getFormattedValue(), ' ';
                            echo "abs(number)";
                        }
                }*/
