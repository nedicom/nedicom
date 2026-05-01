
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
(function() {
    if (typeof window === 'undefined' || typeof document === 'undefined') {
        return;
    }
    
    if (window.__metrikaLoaded) {
        return;
    }
    
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.textContent = `
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) return;
            }
            k = e.createElement(t), a = e.getElementsByTagName(t)[0];
            k.async = 1;
            k.src = r;
            a.parentNode.insertBefore(k, a)
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(24900584, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true,
            trackHash: true
        });
        
        ym(24900584, 'getClientID', function(uid) {
            window.dispatchEvent(new CustomEvent('yandex_metrika_loaded', {
                detail: { ymUid: uid }
            }));
        });
        
        window.__metrikaLoaded = true;
    `;
    
    document.head.appendChild(script);
})();
