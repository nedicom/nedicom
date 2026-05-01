const isBrowser = typeof window !== 'undefined'; // true (window есть)
if (isBrowser) {

    // fonts optimization
    (function () {
        const preloadLinks = [
            //'https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap',
        ];

        preloadLinks.forEach(href => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = href.includes('fonts.googleapis.com') ? 'style' : 'font';
            link.href = href;
            link.crossOrigin = 'anonymous';
            document.head.appendChild(link);
        });
    })();

    // performance optimization
    (function () {
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                if (entries.length > 1000) {
                    console.debug('[Performance] page loaded');
                }
            });
            try {
                observer.observe({ entryTypes: ['largest-contentful-paint', 'first-input'] });
            } catch (e) { }
        }
    })();

    // image lazy loading
    (function () {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.add('loaded');
                        }
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('img.lazy').forEach(img => imageObserver.observe(img));
            });
        }
    })();

    // low connection checker
    (function () {
        if ('connection' in navigator && navigator.connection) {
            const connection = navigator.connection;
            const connectionType = connection.effectiveType || 'unknown';
            window.__networkInfo = {
                type: connectionType,
                downlink: connection.downlink,
                rtt: connection.rtt
            };
        }
    })();

    // service worker check
    (function () {
        if ('serviceWorker' in navigator && location.hostname !== 'localhost') {
            navigator.serviceWorker.getRegistrations().then(registrations => {
                if (registrations.length === 0) {
                    console.debug('[SW] Service Worker not registered');
                }
            }).catch(() => { });
        }
    })();

    // developer debugger
    (function () {
        var ymReal = null;
        var script = document.createElement('script');
        script.async = true;
        script.src = 'https://mc.yandex.ru/metrika/tag.js';
        script.onload = function () {
            if (typeof ym !== 'undefined') {
                window.__ymReal = ym;
                ym(24900584, "init", {
                    clickmap: true,
                    trackLinks: true,
                    accurateTrackBounce: true,
                    webvisor: true,
                    trackHash: true
                });
                ym(24900584, 'getClientID', function (uid) {
                    window.dispatchEvent(new CustomEvent('yandex_metrika_loaded', {
                        detail: { ymUid: uid }
                    }));
                });
            }
        };
        document.head.appendChild(script);
    })();
}