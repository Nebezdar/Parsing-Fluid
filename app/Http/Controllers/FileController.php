<?php

namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function uploadFile(Request $request)
    {
        $file = new ParsingController();
        $files = $request->file('file');

        if (!$request->hasFile('file')) {
            return redirect()->back()->withErrors('Файл не был загружен.');
        }

        if (!$files->isValid()) {
            return redirect()->back()->withErrors('Ошибка загрузки файла.');
        }

        $path = $request->file('file')->storeAs('public', $files->getClientOriginalName());
//dd($request, $fileName, $path);
        if ($path) {
            $file->parsingExcel($files);
        } else {
            return view('welcome', ['file' => $file])->withErrors(['error' => 'ошибка при сохранении файла']);
        }

//        $metafiles = Storage::Files('public');
//
//        for ($i = 0; $i < count($metafiles); $i++) {
//            if (!str_contains($metafiles[$i], 'f.xlsx')) {
//                Storage::delete($metafiles[$i]);
//            }
//        }
//
//        return view('welcome', [
//            'files' => Storage::Files('public')
//        ])->with('success', 'файл загружен успешно');
    }

    public function showProcessedFile()
    {

        $processedFilePath = public_path('public');


        if (!file_exists($processedFilePath)) {
            return 'Обработанный файл не найден.';
        }


        return response()->download($processedFilePath, 'parsing_file.xlsx');
    }
}


