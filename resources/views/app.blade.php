<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Яндекс.Метрика (загружается динамически после согласия) -->
    <script>
        (function() {
            window.yandexMetrikaStatus = 'pending_consent';

            window.Ym = function() {
                var args = Array.from(arguments);

                if (typeof console !== 'undefined') {
                    console.log('[Yandex.Metrica] Метрика не активирована. Требуется согласие на обработку данных.');
                }

                if (args[1] === 'getClientID' && typeof args[2] === 'function') {
                    setTimeout(function() {
                        args[2](null);
                    }, 10);
                }

                return window.Ym;
            };

            window.Ym.a = [];
            window.Ym.l = 1 * new Date();

            window.loadYandexMetrica = function() {
                window.yandexMetrikaStatus = 'error_not_configured';
            };

            if (localStorage.getItem('yandex_metrica_consent') === 'accepted') {
                console.log('[Yandex.Metrica] Согласие обнаружено');
            }
        })();
    </script>

    <noscript>
        <div><img src="https://mc.yandex.ru/watch/24900584" style="position:absolute; left:-9999px;" alt="" /></div>
    </noscript>

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

    @vite(['resources/js/fonts.js'])

    @inertia

    <!-- Fonts to page speed -->
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            document.head.innerHTML +=
                '<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">';
        });
    </script>
    <!-- Fonts to page speed -->

    <!-- Roistat Counter Start -->
    <script>
        (function(w, d, s, h, id) {
            w.roistatProjectId = id;
            w.roistatHost = h;
            w.roistatPage = d.location.href;
            w.roistatReferrer = d.referrer;
            var p = d.location.protocol == "https:" ? "https://" : "http://";
            var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/" + id + "/init?referrer=" + encodeURIComponent(d.location.href);
            var js = d.createElement(s);
            js.charset = "UTF-8";
            js.async = 1;
            js.src = p + h + u;
            var js2 = d.getElementsByTagName(s)[0];
            js2.parentNode.insertBefore(js, js2);
        })(window, document, 'script', 'cloud.roistat.com ', '7a56072f212362140ba2f651690ec0f4');
    </script> <!-- Roistat Counter End -->

</body>

</html>