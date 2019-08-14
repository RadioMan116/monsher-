<?php

class MnServicePHPExcelPHPExcel
{
    private static $included = false;
    private static $alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
        'Q', 'R', 'S', 'T', 'U', 'V'
    );
    private static $iterator = 2;

    public static function create($headers)
    {
        if (self::$included == false)
        {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/PhpExcel/Classes/PHPExcel.php');
            self::$included = true;
        }

        $pExcel = new PHPExcel();
        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();
        $aSheet->setTitle(mb_convert_encoding('Первый лист', 'utf-8', 'windows-1251'));

        foreach ($headers as $ind => $header)
            $aSheet->setCellValue(self::$alphabet[$ind] . '1', mb_convert_encoding($header, 'utf-8', 'windows-1251'));

        return $pExcel;
    }

    public static function write($pExcel, $values)
    {
        $aSheet = $pExcel->getActiveSheet();

        foreach ($values as $ind => $value)
        {
            $aSheet->setCellValue(self::$alphabet[$ind] . self::$iterator, mb_convert_encoding($value, 'utf-8', 'windows-1251'));
        }

        self::$iterator++;
    }

    public static function export($pExcel, $fileName)
    {
        $objWriter = new PHPExcel_Writer_Excel5($pExcel);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }

    public static function save($pExcel, $fileName)
    {
        $objWriter = new PHPExcel_Writer_Excel5($pExcel);
        $objWriter->save($fileName);
    }

    public static function read($fileName)
    {
        if (self::$included == false)
        {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/PhpExcel/Classes/PHPExcel/IOFactory.php');
            self::$included = true;
        }

        $pExcel = PHPExcel_IOFactory::load($fileName);

        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();

        $data = array();

        foreach ($aSheet->getRowIterator() as $row)
        {
            $rowValues = array();
            $cellIterator = $row->getCellIterator();
            foreach($cellIterator as $cell) $rowValues[] = mb_convert_encoding($cell->getCalculatedValue(), 'windows-1251', 'utf-8');

            $data[] = $rowValues;
        }

        return $data;
    }
}
