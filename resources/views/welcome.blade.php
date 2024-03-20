<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Проверка доменов</title>
</head>
<body>


<form action="/uploadFile" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" />
    <button type="submit">Загрузить файл</button>

</form>

<hr>
<h2>Готовый файл</h2>
@foreach($files as $file)
    <div>
        <form action="/download" method="get" enctype="multipart/form-data">
            <a style="display: block; width: 150px; height: 50px"
               href=/download>{{ $file }}</a>
        </form>
    </div>

@endforeach

<hr>
<div class="about" style="display: block">
    <p>
        Данный сервис позволяет провести проверку корректности конечных адресов сайтов, а также проверять статус ответа от сервера
    </p>
    <p>
        Загружается файл формата .xlsx, у которого в столбце "B" указаны адреса сайтов. После обработки, можно скачать файл, в котором напротив указанных данных будут прописаны результаты
    </p>

</div>
</body>
</html>
