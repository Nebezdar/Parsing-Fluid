<?php

namespace App\Http\Controllers;

    use http\Env\Response;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Validator;
    use Symfony\Component\HttpFoundation\StreamedResponse;

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
//            if (!str_contains($metafiles[$i], 'e.xlsx')) {
//                Storage::delete($metafiles[$i]);
//            }
//        }

        return view('welcome', [
            'files' => Storage::Files('public')
        ])->with('success', 'файл загружен успешно');
    }

    public function downloadFile()
    {

        $file = storage_path('parsing.xlsx');
        $headers = ['Content-Type: domains/xlsx'];
        $newName = 'today-xlsx-file'.time().'xlsx';


        return response()->download($file, $newName, $headers);
    }
}


