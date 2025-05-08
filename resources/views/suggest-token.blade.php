<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Yandex Suggest Token</title>
    <script src="https://yastatic.net/s3/passport-sdk/autofill/v1/sdk-suggest-token-with-polyfills-latest.js"></script>
</head>
<body>
<script>
    // Отправляем токен на основной сайт (origin)
    YaSendSuggestToken('https://nedicom.ru', { flag: true });
</script>
</body>
</html>