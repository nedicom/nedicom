<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>На Ваш вопрос ответили</title>
</head>
<body>
    <p>Ответ доступен по ссылке: {{ $mailData['url'] }}</p>
    <p>Ваш вопрос: {{ $mailData['question'] }}</p>
    <p>Отвечать на письмо не нужно. Оно формируется автоматически.</p>
    <p>Обратиться за юридической помоью можно на сайте https://nedicom.ru</p>
</body>
</html>