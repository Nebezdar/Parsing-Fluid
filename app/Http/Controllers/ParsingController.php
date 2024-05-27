<?php

namespace App\Http\Controllers;


use PhpOffice\PhpSpreadsheet\IOFactory;

class ParsingController extends Controller
{
    public function parsingExcel($file)

    {

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file);

        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestDataRow();
        $urls = [];
        set_time_limit(0);
        for ($row = 1; $row <= $highestRow; $row++) {
            $urls[] = [
                'row' => $row,
                'url' => "https://" . mb_strtolower($sheet->getCell('B' . $row)->getValue())
            ];
        }

        $results = array_map(function ($data) {
            $url = $data['url'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 400);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $a = curl_exec($ch);
            $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $finalStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return [
                'row' => $data['row'],
                'finalUrl' => $finalUrl,
                'finalStatus' => $finalStatus
            ];
        }, $urls);

        array_map(function ($result) use ($sheet) {
            $sheet->setCellValue('I' . $result['row'], $result['finalUrl']);
            $sheet->setCellValue('J' . $result['row'], $result['finalStatus']);
        }, $results);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $pathName = storage_path() . "/parsing.xlsx";
        $writer->save($pathName);

        return redirect('/download');
    }
}
