<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @if (env('APP_ENV') != 'local')
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


        <!-- calltouch -->
        <script>
            (function(w, d, n, c) {
                w.CalltouchDataObject = n;
                w[n] = function() {
                    w[n]["callbacks"].push(arguments)
                };
                if (!w[n]["callbacks"]) {
                    w[n]["callbacks"] = []
                }
                w[n]["loaded"] = false;
                if (typeof c !== "object") {
                    c = [c]
                }
                w[n]["counters"] = c;
                for (var i = 0; i < c.length; i += 1) {
                    p(c[i])
                }

                function p(cId) {
                    var a = d.getElementsByTagName("script")[0],
                        s = d.createElement("script"),
                        i = function() {
                            a.parentNode.insertBefore(s, a)
                        },
                        m = typeof Array.prototype.find === 'function',
                        n = m ? "init-min.js" : "init.js";
                    s.async = true;
                    s.src = "https://mod.calltouch.ru/" + n + "?id=" + cId;
                    if (w.opera == "[object Opera]") {
                        d.addEventListener("DOMContentLoaded", i, false)
                    } else {
                        i()
                    }
                }
            })(window, document, "ct", "kzpksm6e");
        </script>
        <!-- calltouch -->
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
