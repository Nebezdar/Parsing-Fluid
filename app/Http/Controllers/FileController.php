<?php

namespace App\Http\Controllers;


    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;


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

        if ($path) {
            $file->parsingExcel($files);
        } else {
            return view('welcome', ['file' => $file])->withErrors(['error' => 'ошибка при сохранении файла']);
        }

        return view('welcome', [
            'files' => Storage::Files('public')
        ])->with('success', 'файл загружен успешно');
    }

    public function downloadFile()
    {

        $file = storage_path('parsing.xlsx');

        return response()->download($file);
    }
}


