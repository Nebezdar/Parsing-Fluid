<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ParsingController extends Controller
{
    public function parsingExcel($file)
    {




        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file);

        $sheet = $spreadsheet->getActiveSheet();


        $highestRow = $sheet->getHighestDataRow();


        for ($row = 2; $row <= $highestRow; $row++) {
            $url = $sheet->getCell('B' . $row)->getValue();


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $a = curl_exec($ch);
            $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $finalStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);


            $sheet->setCellValue('I' . $row, $finalUrl);
            $sheet->setCellValue('J' . $row, $finalStatus);

        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $pathName = storage_path() . "/parsing.xlsx";
//        dd(gettype($pathName));
        $writer->save($pathName);

        return redirect('/download');
    }
}
