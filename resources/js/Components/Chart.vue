<!-- resources/js/Components/Charts/MiniChart.vue -->
<template>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <!-- Заголовок -->
        <div class="flex justify-between items-center mb-4">
            <h5 class="font-medium text-gray-700">Просмотры за неделю</h5>
            <div v-if="statistics.is_real_data" class="text-xs text-green-500">✓ Статистика</div>
        </div>

        <!-- График -->
        <div class="relative h-48 mb-4">
            <!-- Сетка с подписями -->
            <div class="absolute inset-0 flex flex-col justify-between">
                <div class="border-t border-gray-200 relative">
                    <span class="absolute left-0 -top-2 text-xs text-gray-400">{{ maxViews }}</span>
                </div>
                <div class="border-t border-gray-100"></div>
                <div class="border-t border-gray-100"></div>
                <div class="border-t border-gray-100"></div>
                <div class="border-t border-gray-200 relative">
                    <span class="absolute left-0 -top-2 text-xs text-gray-400">0</span>
                </div>
            </div>

            <!-- Колонки -->
            <div class="absolute bottom-0 left-0 right-0 h-40 flex items-end justify-between px-1">
                <div v-for="(day, index) in statistics.viewsData" :key="day.date"
                    class="flex-1 flex flex-col items-center mx-1 group h-full">
                    
                    <!-- Основной контейнер для колонок - растягиваем на всю высоту -->
                    <div class="relative w-full h-full flex flex-col justify-end items-center">
                        
                        <!-- Контейнер для стека колонок -->
                        <div class="relative w-full" :style="{ height: 'calc(100% - 20px)' }">
                            
                            <!-- Темно-синяя (вовлеченные) - СНИЗУ -->
                            <div v-if="day.engaged > 0"
                                class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-full bg-blue-600 transition-all duration-300 hover:bg-blue-700 hover:shadow-md z-10"
                                :style="{
                                    height: getBarHeight(day.engaged) + '%',
                                    minHeight: '12px'
                                }">
                            </div>
                            
                            <!-- Светлая синяя колонка (все просмотры) - ПОСЕРЕДИНЕ -->
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-full bg-blue-400 transition-all duration-300 hover:bg-blue-500 hover:shadow-md"
                                :style="{
                                    height: getBarHeight(day.views) + '%',
                                    minHeight: '12px'
                                }">
                            </div>
                            
                            <!-- Красная часть (конверсии) - СВЕРХУ -->
                            <div v-if="day.conversions > 0"
                                class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-full bg-red-500 rounded-t transition-all duration-300 hover:bg-red-600 hover:shadow-md z-20"
                                :style="{
                                    height: getBarHeight(day.conversions) + '%',
                                    minHeight: '12px',
                                    bottom: getBarHeight(day.views) + '%' // Располагаем сверху синей колонки
                                }">
                            </div>
                            
                            <!-- Числа (над колонкой) -->
                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 text-xs font-semibold text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                <div class="flex flex-col items-center gap-0.5">
                                    <div class="text-gray-800">{{ day.views }}</div>
                                    <div v-if="day.engaged > 0" class="text-blue-600">{{ day.engaged }}</div>
                                    <div v-if="day.conversions > 0" class="text-red-600">{{ day.conversions }}</div>
                                </div>
                            </div>
                            
                            <!-- Тултип -->
                            <div class="absolute -top-20 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-30">
                                <div class="bg-gray-900 text-white text-xs px-3 py-2 rounded shadow-lg">
                                    <div class="font-medium mb-1">{{ day.fullDate }}</div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                            <span>Все просмотры: {{ day.views }}</span>
                                        </div>
                                        <div v-if="day.engaged" class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                                            <span>Вовлеченные: {{ day.engaged }}</span>
                                        </div>
                                        <div v-if="day.conversions" class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                            <span>Конверсии: {{ day.conversions }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                            </div>
                            
                        </div>
                        
                        <!-- Дата -->
                        <div class="mt-2 text-xs text-gray-600 font-medium h-5 flex items-center">
                            {{ day.formattedDate }}
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Итог и легенда -->
        <div class="pt-3 border-t border-gray-100">
            <!-- Итог -->
            <div class="text-sm text-gray-600 mb-3">
                Всего: <span class="font-semibold text-gray-800">{{ statistics.totalViews }}</span> просмотров
                <span v-if="statistics.totalEngaged > 0" class="ml-3">
                    Вовлечение: <span class="font-semibold text-blue-600">{{ statistics.totalEngaged }}</span>
                </span>
                <span v-if="statistics.totalConversions > 0" class="ml-3">
                    Конверсии: <span class="font-semibold text-red-600">{{ statistics.totalConversions }}</span>
                </span>
            </div>
            
            <!-- Легенда -->
            <div class="flex items-center justify-center gap-4 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-t bg-blue-400"></div>
                    <span>Все просмотры</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-t bg-blue-600"></div>
                    <span>Вовлеченные</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-t bg-red-500"></div>
                    <span>Конверсии</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    statistics: {
        type: Object,
        required: true,
        default: () => ({
            viewsData: [],
            totalViews: 0,
            totalEngaged: 0,
            totalConversions: 0,
            is_real_data: false
        })
    }
})

const maxViews = computed(() => {
    const views = props.statistics?.viewsData?.map(d => d.views) || []
    const max = Math.max(...views, 1)
    return Math.max(max, 10) // Минимум 10 для наглядности
})

// Функция для вычисления высоты колонки
const getBarHeight = (value) => {
    if (value === 0) return 2 // Минимум 2% для нулевых значений
    
    // Базовая высота в процентах
    let height = (value / maxViews.value) * 100
    
    // Гарантируем минимальную видимую высоту
    if (height < 10) {
        // Для маленьких значений увеличиваем видимость
        height = 10 + (value * 2)
    }
    
    // Ограничиваем максимум
    return Math.min(height, 100)
}
</script>

<style scoped>
/* Анимация роста колонок */
.bg-blue-400, .bg-blue-600, .bg-red-500 {
    animation: growBar 0.5s ease-out;
}

@keyframes growBar {
    from {
        height: 0;
        opacity: 0.5;
    }
    to {
        height: var(--target-height);
        opacity: 1;
    }
}

/* Пульсация для красной колонки */
.bg-red-500 {
    animation: growBar 0.5s ease-out, pulse 2s infinite 0.5s;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}
</style>