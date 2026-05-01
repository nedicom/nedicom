<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>


    <!-- /Yandex.Metrika counter -->

    <!-- Google tag deleted  forever-->

    @if(env('APP_ENV') !== 'local')
        <script src="https://vk.com/js/api/openapi.js?169" type="text/javascript"></script>
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!--
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    -->

    <!-- Scripts -->

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<style>
    /*@font-face {
        font-family: 'nunito';
        src: local('Pacifico Regular'), local('Pacifico-Regular'),
            url(https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap) format('nunito');
        font-display: swap;
    }*/
</style>


<body class="font-sans antialiased">



    @inertia

    <!-- Fonts to page speed -->
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            document.head.innerHTML +=
                '<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">';
        });
    </script>
    <!-- Fonts to page speed -->

</body>

</html>