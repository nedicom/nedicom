<template></template>

<script setup>
import { defineProps, onMounted, onUnmounted, ref, watch } from 'vue'

const props = defineProps({
  backendurl: String,
  tracking: {
    type: Object,
    default: () => ({})
  }
})

const ymUid = ref(null)
const isBrowser = typeof window !== 'undefined'
const engagementTimer = ref(null)
const isEngaged = ref(false)

// Отправляем когда меняется isEngaged или приходит ymUid
watch([() => ymUid.value, () => isEngaged.value], () => {
  if (ymUid.value && props.tracking?.visit_uuid && props.backendurl) {
    sendToServer()
  }
})

onMounted(() => {
  if (!isBrowser) return
  
  console.log('🎯 Tracking mounted for:', props.backendurl)
  
  // Таймер вовлеченности
  startEngagementTimer(3000) // 3 сек для теста
  
  // Яндекс.Метрика
  const handleYandexLoaded = (event) => {
    ymUid.value = event.detail?.ymUid
    console.log('📡 Яндекс.Метрика:', ymUid.value)
  }
  
  window.addEventListener('yandex_metrika_loaded', handleYandexLoaded)
  
  // Проверка кук
  setTimeout(() => {
    const existingUid = getCookie('_ym_uid')
    if (existingUid && !ymUid.value) {
      console.log('🍪 Яндекс из кук:', existingUid)
      ymUid.value = existingUid
    }
  }, 1000)
  
  // Очистка
  onUnmounted(() => {
    window.removeEventListener('yandex_metrika_loaded', handleYandexLoaded)
    if (engagementTimer.value) clearTimeout(engagementTimer.value)
  })
})

function startEngagementTimer(ms = 30000) {
  if (engagementTimer.value) clearTimeout(engagementTimer.value)
  
  engagementTimer.value = setTimeout(() => {
    isEngaged.value = true
    console.log('⏱️ Пользователь вовлечен (>' + ms/1000 + ' сек)')
  }, ms)
}

function sendToServer() {
  console.log('🚀 Отправка:', {
    url: props.backendurl,
    is_engaged: isEngaged.value
  })
  
  fetch('/api/track', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify({
      _ym_uid: ymUid.value,
      visit_uuid: props.tracking.visit_uuid,
      url: props.backendurl,
      is_engaged: isEngaged.value
    })
  })
  .then(r => r.json())
  .then(data => console.log('✅ Ответ:', data))
  .catch(err => console.error('❌ Ошибка:', err))
}

function getCookie(name) {
  if (!isBrowser) return null
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'))
  return match ? decodeURIComponent(match[2]) : null
}
</script>