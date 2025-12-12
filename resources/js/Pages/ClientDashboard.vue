<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

// Получаем данные из Inertia
const props = defineProps({
    clientData: Object
})
</script>

<template>

        <h1 class="text-2xl font-bold mb-4">Личный кабинет</h1>
{{ props.clientData }}
        <!-- Загрузка -->




  
</template>

<style scoped>
/* Минимальные стили */
</style>