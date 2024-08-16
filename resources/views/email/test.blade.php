<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>На Ваш вопрос ответили</title>
</head>
<body>
    <p>На Ваш вопрос: <em>{{ $mailData['question'] }}<em></p>
    <p>Ответ доступен по ссылке: {{ $mailData['url'] }}</p>
    
    <blockquote>
        <p class="has-line-data" data-line-start="26" data-line-end="33">
            Отвечать на письмо не нужно. Оно формируется автоматически.
    </p>
        </blockquote>

    <p></p>
    <a target="_blank" href="https://nedicom.ru">
        <img src="https://nedicom.ru/storage/usr/1/avatar/1713110600avatar.webp" alt="" width="260" style="display:block">
    </a>
    <p>Обратиться за юридической помоью можно на сайте https://nedicom.ru</p>
</body>
</html>