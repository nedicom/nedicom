// resources/js/tracking.js

/**
 * Трекинг посетителей для nedicom.ru
 * Автоматически собирает UTM метки, Яндекс куки и информацию о посетителе
 */

// Функция получения куки
const getCookie = (name) => {
    const matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ))
    return matches ? decodeURIComponent(matches[1]) : null
}

// Собираем данные для трекинга
const collectTrackingData = () => {
    const data = {
        // Основные данные
        current_url: window.location.href,
        path: window.location.pathname,
        referer: document.referrer,
        
        // Яндекс куки
        yandex_uid: getCookie('_ym_uid'),
        yandex_client_id: getCookie('yandexuid') || getCookie('ycid'),
        
        // Информация о браузере
        screen_width: window.screen.width,
        screen_height: window.screen.height,
        language: navigator.language,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        
        // Пользовательские данные из Laravel (если передаются)
        user_email: window.Laravel?.user?.email || null,
        user_id: window.Laravel?.user?.id || null,
    }
    
    // Добавляем UTM метки из URL
    const urlParams = new URLSearchParams(window.location.search)
    const utmKeys = [
        'utm_source', 'utm_medium', 'utm_campaign', 
        'utm_content', 'utm_term', 'gclid', 'yclid'
    ]
    
    utmKeys.forEach(key => {
        const value = urlParams.get(key)
        if (value) data[key] = value
    })
    
    // Убираем пустые значения
    return Object.fromEntries(
        Object.entries(data).filter(([_, v]) => v != null && v !== '')
    )
}

// Отправка данных на сервер
const sendTrackingData = async (data) => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
        
        const response = await fetch('/api/visitor/track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(data)
        })
        
        const result = await response.json()
        
        // Устанавливаем куку если сервер вернул новый visitor_id
        if (result.success && result.visitor_id && !getCookie('nedicom_vid')) {
            document.cookie = `nedicom_vid=${result.visitor_id}; max-age=${2*365*24*60*60}; path=/; SameSite=Lax`
            
            console.log('🎯 Трекинг: установлен visitor_id', result.visitor_id)
        }
        
        if (result.success) {
            console.log('✅ Трекинг: данные успешно отправлены')
        }
        
        return result
        
    } catch (error) {
        console.error('❌ Ошибка трекинга:', error)
        return { success: false, error: error.message }
    }
}

// Основная функция инициализации трекинга
const initVisitorTracking = () => {
    // Проверяем, что мы в браузере (не SSR)
    if (typeof window === 'undefined') return
    
    console.log('🚀 Инициализация трекинга посетителей...')
    
    // Собираем данные
    const trackingData = collectTrackingData()
    
    if (Object.keys(trackingData).length === 0) {
        console.log('📭 Трекинг: нет данных для отправки')
        return
    }
    
    console.log('📤 Трекинг: отправляемые данные', trackingData)
    
    // Отправляем сразу
    setTimeout(() => sendTrackingData(trackingData), 500)
    
    // И через 5 секунд для надежности
    setTimeout(() => sendTrackingData(trackingData), 5000)
    
    // Отслеживаем SPA навигацию (для Inertia)
    if (typeof window.history !== 'undefined') {
        const originalPushState = window.history.pushState
        const originalReplaceState = window.history.replaceState
        
        // Перехватываем изменения истории
        window.history.pushState = function(...args) {
            originalPushState.apply(this, args)
            setTimeout(() => sendTrackingData(collectTrackingData()), 1000)
        }
        
        window.history.replaceState = function(...args) {
            originalReplaceState.apply(this, args)
            setTimeout(() => sendTrackingData(collectTrackingData()), 1000)
        }
        
        // Отслеживаем кнопки назад/вперед
        window.addEventListener('popstate', () => {
            setTimeout(() => sendTrackingData(collectTrackingData()), 1000)
        })
    }
}

// Экспортируем функции для использования в других местах
export {
    getCookie,
    collectTrackingData,
    sendTrackingData,
    initVisitorTracking
}

// Автоматическая инициализация при загрузке скрипта
if (typeof window !== 'undefined') {
    // Ждем загрузки DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVisitorTracking)
    } else {
        initVisitorTracking()
    }
}