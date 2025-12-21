// Максимально простой трекинг Яндекс кук

// 1. Функция получить Яндекс куки
function getYandexCookies() {
    const cookies = document.cookie.split(';')
    const result = {}
    
    cookies.forEach(cookie => {
        const [key, value] = cookie.trim().split('=')
        if (['_ym_uid', 'yandexuid', 'yandex_login'].includes(key)) {
            result[key] = decodeURIComponent(value)
        }
    })
    
    return result
}

// 2. Функция отправить на сервер
async function sendToServer() {
    const cookies = getYandexCookies()
    
    if (!cookies._ym_uid && !cookies.yandexuid && !cookies.yandex_login) return
    
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content
        
        await fetch('/api/track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf || ''
            },
            body: JSON.stringify({
                ...cookies,
                url: window.location.href
            })
        })
    } catch (e) {
        console.log('Ошибка отправки:', e)
    }
}

// 3. Запустить трекинг
function initTracking() {
    if (typeof window === 'undefined') return
    
    // Отправить при загрузке
    setTimeout(sendToServer, 1000)
    
    // Отслеживать навигацию
    if (window.Inertia) {
        window.Inertia.on('navigate', () => {
            setTimeout(sendToServer, 500)
        })
    }
}

// Экспорт
export { initTracking }