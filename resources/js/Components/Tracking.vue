<template></template>

<script setup>
import { defineProps, onMounted, onUnmounted, ref } from 'vue'

const props = defineProps({
  backendurl: String,
  tracking: {
    type: Object,
    default: () => ({})
  }
})

const hasSent = ref(false)
const ymUid = ref(null)
const isMobile = ref(false)

onMounted(() => {

  if (typeof window === 'undefined') return

  // Определяем мобильное устройство
  isMobile.value = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent)

  if (import.meta.env.DEV) {
    console.log('🎯 Tracking mounted:', {
      url: props.backendurl
    })
  }

  // Таймер 3 секунды
  const timer = setTimeout(() => {
    sendToServer()
  }, 3000)

  // Очистка
  onUnmounted(() => {
    clearTimeout(timer)
    if (!hasSent.value) {
      sendToServer()
    }
  })
})

function sendToServer() {
  // Проверка на дублирование
  if (hasSent.value) {
    if (import.meta.env.DEV) console.log('🔄 Уже отправлено')
    return
  }

  // Проверка обязательных полей
  if (!props.tracking?.visit_uuid) {
    if (import.meta.env.DEV) console.log('❌ Нет visit_uuid')
    return
  }

  if (!props.backendurl) {
    if (import.meta.env.DEV) console.log('❌ Нет backendurl')
    return
  }

  hasSent.value = true

  // Формируем данные
  const formData = new FormData()
  formData.append('visit_uuid', props.tracking.visit_uuid)
  formData.append('url', props.backendurl)

  // CSRF токен
  const csrfToken = getCsrfToken()
  if (csrfToken) {
    formData.append('_token', csrfToken)
  }

  if (import.meta.env.DEV) {
    const data = {
      visit_uuid: props.tracking.visit_uuid,
      url: props.backendurl,
      ym_uid: '',
      has_csrf: !!csrfToken
    }
    console.log('🚀 Отправка вовлечения:', data.url)
  }

  // Отправляем
  fetch('/events', {
    method: 'POST',
    body: formData,
  })
    .then(async response => {
      if (import.meta.env.DEV) {
        console.log(`📊 HTTP статус: ${response.status}`)
      }

      if (response.ok) {
        const data = await response.json()
        if (import.meta.env.DEV) {
          console.log('✅ Успешно сохранено:', data.message)
        }
      } else {
        // Пробуем прочитать текст ошибки
        try {
          const error = await response.json()
          console.error('❌ Ошибка сервера:', error)
        } catch {
          console.error(`❌ HTTP ошибка ${response.status}`)
        }
      }
    })
    .catch(error => {
      if (import.meta.env.DEV) {
        console.error('❌ Ошибка сети:', error)
      }
    })
}

function getCsrfToken() {
  try {
    // 1. Из meta тега (стандартный способ Laravel)
    const meta = document.querySelector('meta[name="csrf-token"]')
    if (meta && meta.content) {
      return meta.content
    }

    // 2. Из input поля
    const input = document.querySelector('input[name="_token"]')
    if (input && input.value) {
      return input.value
    }

    // 3. Из кук (для инерции и SPA)
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/)
    if (match) {
      return decodeURIComponent(match[1])
    }

    return ''
  } catch (e) {
    return ''
  }
}
</script>