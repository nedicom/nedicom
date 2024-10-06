<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $mailData['message'] }}</title>
</head>
<body>
    <p>Пользователь: {{ $mailData['user'] }}</p>
    <p>Заголовок: {{ $mailData['title'] }}</p>
    <p>Доступно по ссылке: {{ $mailData['url'] }}</p>
    
    <blockquote>
        <p class="has-line-data" data-line-start="26" data-line-end="33">
            Отвечать на письмо не нужно. Оно формируется автоматически.
        </p>
    </blockquote>

        <p>С уважением, команда ИП Мина О. В. (nedicom.ru @tm) Обратиться за юридической помощью можно на сайте https://nedicom.ru</p>
    
    <a target="_blank" href="https://nedicom.ru">
        <img src="https://nedicom.ru/storage/images/landing/main/1280on600.webp" 
        alt="" width="400" style="display:block">
    </a>
    
</body>
</html>