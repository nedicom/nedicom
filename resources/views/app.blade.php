<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @if (env('APP_ENV') != 'local')
        <!-- Google tag (gtag.js) -->

        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-16952783137"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'AW-16952783137');
        </script>

        <!-- Event snippet for Page view conversion page -->
        <script>
            gtag('event', 'conversion', {
                'send_to': 'AW-16952783137/dqBuCMzIqbAaEKHi25M_',
                'value': 1.0,
                'currency': 'EUR'
            });
        </script>
        <!-- Google tag (gtag.js) -->


        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function(m, e, t, r, i, k, a) {
                m[i] = m[i] || function() {
                    (m[i].a = m[i].a || []).push(arguments)
                };
                m[i].l = 1 * new Date();
                for (var j = 0; j < document.scripts.length; j++) {
                    if (document.scripts[j].src === r) {
                        return;
                    }
                }
                k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(
                    k, a)
            })
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(24900584, "init", {
                clickmap: true,
                trackLinks: true,
                accurateTrackBounce: true,
                webvisor: true,
                trackHash: true
            });
        </script>
        <noscript>
            <div><img src="https://mc.yandex.ru/watch/24900584" style="position:absolute; left:-9999px;" alt="" />
            </div>
        </noscript>
        <!-- /Yandex.Metrika counter -->

        <!-- Top.Mail.Ru counter -->
        <script type="text/javascript">
            var _tmr = window._tmr || (window._tmr = []);
            _tmr.push({
                id: "3623031",
                type: "pageView",
                start: (new Date()).getTime()
            });
            (function(d, w, id) {
                if (d.getElementById(id)) return;
                var ts = d.createElement("script");
                ts.type = "text/javascript";
                ts.async = true;
                ts.id = id;
                ts.src = "https://top-fwz1.mail.ru/js/code.js";
                var f = function() {
                    var s = d.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(ts, s);
                };
                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(document, window, "tmr-code");
        </script>
        <noscript>
            <div><img src="https://top-fwz1.mail.ru/counter?id=3623031;js=na" style="position:absolute;left:-9999px;"
                    alt="Top.Mail.Ru" /></div>
        </noscript>
        <!-- /Top.Mail.Ru counter -->
    @endif

    <script src="https://vk.com/js/api/openapi.js?169" type="text/javascript"></script>
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

    <!-- Grecaptcha
        <script src="https://www.google.com/recaptcha/api.js?render=6Lf0-tAZAAAAAIxKP1YOtKrCfqSm_yl3QF-IzglK"></script>
         Grecaptcha -->


</body>

</html>
