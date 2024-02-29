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
        $fileName = $request->file('file');

        if (!$request->hasFile('file')) {
            return redirect()->back()->withErrors('Файл не был загружен.');
        }

        if (!$fileName->isValid()) {
            return redirect()->back()->withErrors('Ошибка загрузки файла.');
        }

        $path = Storage::putFileAs('public', $request->file('file'), $fileName->getClientOriginalName());

        if ($path) {
            $file->parsingExcel($path);
        } else {
            return view('welcome', ['file' => $file])->withErrors(['error' => 'ошибка при сохранении файла']);
        }

        $megafiles = Storage::Files('public');

        for ($i = 0; $i < count($megafiles); $i++) {
            if (!str_contains($megafiles[$i], 'f.xlsx')) {
                Storage::delete($megafiles[$i]);
            }
        }

        return view('welcome', [
            'files' => Storage::Files('/excel')
        ])->with('success', 'файл загружен успешно');
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


