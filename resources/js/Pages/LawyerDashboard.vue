<script setup>
import { computed, ref, reactive, watch, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import {
    ArrowPathIcon,
    ChevronDownIcon,
    ChevronUpIcon,
    EyeIcon,
    CalendarIcon,
    ChartBarIcon,
    DocumentTextIcon,
    MegaphoneIcon,
    InformationCircleIcon,
    TagIcon,
    CurrencyDollarIcon,
    MapPinIcon,
    PhoneIcon,
    UserGroupIcon,
    ArrowTrendingUpIcon
} from '@heroicons/vue/24/outline'
import { Head } from '@inertiajs/vue3'
import MainHeader from "@/Layouts/MainHeader.vue";
import MainFooter from "@/Layouts/MainFooter.vue";

const props = defineProps({
    auth: Object,
    demoData: Object,
    userData: Object,
})

const isLoading = ref(false)

// Определяем, какие данные использовать
const isDemo = computed(() => props.demoData?.isDemo || false)
const dataSource = computed(() => isDemo.value ? props.demoData : props.userData)

// Получаем текущие фильтры из бэкенда
const backendFilters = computed(() => dataSource.value?.filters || {})

// Основные состояния - инициализируем из бэкенда или по умолчанию
const activeTab = ref(backendFilters.value.activeTab || 'articles')
const sortField = ref(backendFilters.value.sortField || 'date')
const sortDirection = ref(backendFilters.value.sortOrder || 'desc')
const currentPage = ref(backendFilters.value.page || 1)

// Локальные фильтры
const filters = reactive({
    dateFrom: '',
    dateTo: '',
    status: [backendFilters.value.status || ''].filter(Boolean),
    search: backendFilters.value.search || ''
})

const showMobileFilters = ref(false)

// Дебаунс таймеры
let searchDebounceTimer = null
let isRequestInProgress = false
let requestId = 0

// Упрощенная функция обновления URL
const updateURL = () => {
    if (isLoading.value || isRequestInProgress) return

    clearTimeout(searchDebounceTimer)

    searchDebounceTimer = setTimeout(() => {
        isLoading.value = true
        const currentRequestId = ++requestId

        const urlParams = new URLSearchParams()

        if (activeTab.value !== 'articles') {
            urlParams.set('tab', activeTab.value)
        }

        if (filters.search) {
            urlParams.set('q', filters.search)
        }

        if (sortField.value !== 'date') {
            urlParams.set('sort', sortField.value)
        }

        if (sortDirection.value !== 'desc') {
            urlParams.set('order', sortDirection.value)
        }

        if (currentPage.value > 1) {
            urlParams.set('page', currentPage.value)
        }

        if (filters.status.length === 1 && filters.status[0]) {
            urlParams.set('status', filters.status[0])
        }

        const queryString = urlParams.toString()
        const url = queryString ? `/lawyerdashboard?${queryString}` : '/lawyerdashboard'

        if (currentRequestId === requestId) {
            isRequestInProgress = true
            window.location.href = url
        }
    }, 300)
}

// Обработчики событий
const handleTabChange = (tabId) => {
    activeTab.value = tabId
    currentPage.value = 1
    updateURL()
}

const handleSort = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortField.value = field
        sortDirection.value = 'desc'
    }
    currentPage.value = 1
    updateURL()
}

const handleSortSelect = (event) => {
    sortField.value = event.target.value
    currentPage.value = 1
    updateURL()
}

const setSortOrder = (order) => {
    sortDirection.value = order
    currentPage.value = 1
    updateURL()
}

const handleSearchInput = () => {
    clearTimeout(searchDebounceTimer)
    searchDebounceTimer = setTimeout(() => {
        currentPage.value = 1
        updateURL()
    }, 500)
}

const handleFilterChange = () => {
    currentPage.value = 1
    updateURL()
}

const toggleStatusFilter = (status) => {
    if (filters.status.includes(status)) {
        filters.status = filters.status.filter(s => s !== status)
    } else {
        filters.status = [status]
    }
    currentPage.value = 1
    updateURL()
}

const applyFiltersAndUpdateURL = () => {
    showMobileFilters.value = false
    currentPage.value = 1
    updateURL()
}

const resetFilters = () => {
    filters.dateFrom = ''
    filters.dateTo = ''
    filters.status = ['']
    filters.search = ''
    sortField.value = 'date'
    sortDirection.value = 'desc'
    currentPage.value = 1
    updateURL()
}

const goToPage = (pageNum) => {
    if (pageNum < 1 || pageNum > (currentPagination.value?.total_pages || 1)) return
    currentPage.value = pageNum
    updateURL()
}

// Вычисляемые свойства
const tabs = computed(() => {
    const baseTabs = [
        { id: 'articles', label: 'Статьи', icon: DocumentTextIcon },
        { id: 'ads', label: 'Объявления', icon: MegaphoneIcon },
        { id: 'stats', label: 'Статистика', icon: ChartBarIcon }
    ]

    if (dataSource.value) {
        baseTabs[0].count = dataSource.value.articles?.count || 0
        baseTabs[1].count = dataSource.value.uslugi?.count || 0
    }

    return baseTabs
})

// Текущие данные в зависимости от вкладки
const currentData = computed(() => {
    if (!dataSource.value) return { data: [], count: 0, totalViews: 0, last7DaysViews: [], pagination: null }

    if (activeTab.value === 'articles') {
        return {
            data: dataSource.value.articles?.data || [],
            count: dataSource.value.articles?.count || 0,
            totalViews: dataSource.value.articles?.totalViews || 0,
            last7DaysViews: dataSource.value.articles?.last7DaysViews || [],
            pagination: dataSource.value.articles?.pagination || null
        }
    } else if (activeTab.value === 'ads') {
        return {
            data: dataSource.value.uslugi?.data || [],
            count: dataSource.value.uslugi?.count || 0,
            totalViews: dataSource.value.uslugi?.totalViews || 0,
            last7DaysViews: dataSource.value.uslugi?.last7DaysViews || [],
            pagination: dataSource.value.uslugi?.pagination || null
        }
    }

    return { data: [], count: 0, totalViews: 0, last7DaysViews: [], pagination: null }
})

// Статистика конверсий для текущей вкладки
const conversionsStats = computed(() => {
    if (!dataSource.value) return null

    if (activeTab.value === 'articles') {
        return dataSource.value.articles?.conversions
    } else if (activeTab.value === 'ads') {
        return dataSource.value.uslugi?.conversions
    }

    return null
})

// Статистика для текущей вкладки
const currentStats = computed(() => {
    if (activeTab.value === 'articles') {
        return {
            label: 'Статей',
            totalViews: dataSource.value?.articles?.totalViews || 0,
            count: dataSource.value?.articles?.count || 0,
            last7DaysViews: dataSource.value?.articles?.last7DaysViews || [],
            conversionRate: dataSource.value?.articles?.conversions?.rate || 0
        }
    } else if (activeTab.value === 'ads') {
        return {
            label: 'Объявлений',
            totalViews: dataSource.value?.uslugi?.totalViews || 0,
            count: dataSource.value?.uslugi?.count || 0,
            last7DaysViews: dataSource.value?.uslugi?.last7DaysViews || [],
            conversionRate: dataSource.value?.uslugi?.conversions?.rate || 0
        }
    }

    // Общая статистика для вкладки "Статистика"
    return {
        label: 'Всего',
        totalViews: dataSource.value?.stats?.totalViews || 0,
        count: dataSource.value?.stats?.totalMaterials || 0,
        last7DaysViews: dataSource.value?.stats?.last7DaysViews || [],
        conversionRate: dataSource.value?.stats?.conversionRate || 0
    }
})

// Пагинационные данные
const currentPagination = computed(() => currentData.value.pagination)

// Пагинационные страницы
const paginationPages = computed(() => {
    if (!currentPagination.value) return []

    const totalPages = currentPagination.value.total_pages
    const current = currentPagination.value.current_page

    if (totalPages <= 7) {
        return Array.from({ length: totalPages }, (_, i) => i + 1)
    }

    const pages = []
    pages.push(1)

    if (current > 3) pages.push('...')

    for (let i = Math.max(2, current - 1); i <= Math.min(totalPages - 1, current + 1); i++) {
        pages.push(i)
    }

    if (current < totalPages - 2) pages.push('...')
    if (totalPages > 1) pages.push(totalPages)

    return pages
})

// Фильтрованные материалы
const filteredMaterials = computed(() => currentData.value.data || [])

// Опции для фильтров
const statusOptions = [
    { value: '', label: 'Все статусы' },
    { value: 'active', label: 'Активные' },
    { value: 'inactive', label: 'Неактивные' },
    { value: 'moderation', label: 'На модерации' }
]

const sortOptions = computed(() => {
    const options = [
        { value: 'date', label: 'По дате' },
        { value: 'views', label: 'По просмотрам' },
        { value: 'title', label: 'По названию' }
    ]

    if (currentData.value.data.some(m => m.conversions)) {
        options.push({ value: 'conversions', label: 'По конверсии' })
    }

    return options
})

// Компьютед свойства
const sortArrowIcon = computed(() => {
    return sortDirection.value === 'asc' ? ChevronUpIcon : ChevronDownIcon
})

// Вспомогательные методы
const getStatusText = (status) => {
    switch (status) {
        case 'active': return 'Активна'
        case 'inactive': return 'Неактивна'
        case 'moderation': return 'На модерации'
        default: return 'Неизвестно'
    }
}

const getStatusClass = (status) => {
    switch (status) {
        case 'active': return 'bg-green-100 text-green-800'
        case 'inactive': return 'bg-gray-100 text-gray-800'
        case 'moderation': return 'bg-yellow-100 text-yellow-800'
        default: return 'bg-gray-100 text-gray-800'
    }
}

const getConversionRateClass = (rate) => {
    if (rate >= 50) return 'text-green-600'
    if (rate >= 30) return 'text-yellow-600'
    if (rate >= 10) return 'text-orange-600'
    return 'text-red-600'
}

const formatDate = (dateString) => {
    try {
        return new Date(dateString).toLocaleDateString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        })
    } catch (e) {
        return dateString
    }
}

const formatConversionRate = (rate) => {
    return rate ? rate.toFixed(1) + '%' : '0%'
}

const formatNumber = (num) => {
    return num ? num.toLocaleString('ru-RU') : '0'
}

const viewStatistics = (materialId) => {
    console.log('View statistics for material:', materialId)
}

const editMaterial = (materialId) => {
    if (activeTab.value === 'articles') {
        window.open(`/articles/${materialId}/edit`, '_blank')
    } else {
        window.open(`/uslugi/${materialId}/edit`, '_blank')
    }
}

const goToAddMaterial = () => {
    if (activeTab.value === 'articles') {
        window.open('/articles/create', '_blank')
    } else {
        window.open('/uslugi/create', '_blank')
    }
}

// Инициализация при загрузке
onMounted(() => {
    console.log('Dashboard initialized with SSR filters:', backendFilters.value)
})

// Наблюдаем за изменениями параметров из бэкенда
watch(() => props.userData?.filters, (newFilters) => {
    if (newFilters) {
        if (newFilters.activeTab) activeTab.value = newFilters.activeTab
        if (newFilters.search !== undefined) filters.search = newFilters.search
        if (newFilters.sortField) sortField.value = newFilters.sortField
        if (newFilters.sortOrder) sortDirection.value = newFilters.sortOrder
        if (newFilters.page) currentPage.value = newFilters.page
        if (newFilters.status !== undefined) filters.status = [newFilters.status].filter(Boolean)
    }
}, { immediate: true })

// Наблюдаем за демо-данными
watch(() => props.demoData?.filters, (newFilters) => {
    if (newFilters && isDemo.value) {
        if (newFilters.activeTab) activeTab.value = newFilters.activeTab
        if (newFilters.search !== undefined) filters.search = newFilters.search
        if (newFilters.sortField) sortField.value = newFilters.sortField
        if (newFilters.sortOrder) sortDirection.value = newFilters.sortOrder
        if (newFilters.page) currentPage.value = newFilters.page
        if (newFilters.status !== undefined) filters.status = [newFilters.status].filter(Boolean)
    }
}, { immediate: true })
</script>

<template>

    <Head>
        <title>Личный кабинет юриста nedicom.ru</title>
        <meta name="description" content="Личный кабинет юриста nedicom.ru" />
        <meta property="og:title" content="Личный кабинет юриста nedicom.ru" />
        <meta property="og:description" content="Личный кабинет юриста nedicom.ru" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://nedicom.ru/lawyerdashboard" />
        <meta property="og:site_name" content="nedicom.ru" />
        <meta property="og:locale" content="ru_RU" />
        <link rel="canonical" href="https://nedicom.ru/lawyerdashboard" />
    </Head>

    <div class="min-h-screen">
        <MainHeader :auth="auth" />

        <!-- Основной контент -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Демо-баннер -->
            <div v-if="isDemo" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start gap-3">
                <InformationCircleIcon class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                <div class="text-sm text-blue-700">
                    <strong>Демо-режим</strong>. Вы просматриваете демонстрационные данные.
                    <Link v-if="!auth" href="/login" class="text-blue-600 font-medium hover:text-blue-800 ml-1">
                        Авторизуйтесь
                    </Link>
                    для просмотра ваших материалов.
                </div>
            </div>

            <!-- Заголовок -->
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    {{ isDemo ? 'Демо: Личный кабинет юриста' : 'Личный кабинет' }}
                </h1>
                <p class="text-gray-600 mt-2">
                    {{ isDemo ? 'Демонстрация возможностей личного кабинета' : 'Статистика по вашим материалам' }}
                </p>
            </div>

            <!-- Мобильный селект для табов -->
            <div class="md:hidden mb-6">
                <select v-model="activeTab" @change="handleTabChange"
                    class="w-full p-3 border border-gray-300 rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option v-for="tab in tabs" :key="tab.id" :value="tab.id">
                        {{ tab.label }} <span v-if="tab.count">({{ tab.count }})</span>
                    </option>
                </select>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Боковая панель для фильтров -->
                <div class="lg:w-1/4">
                    <!-- Кнопка для открытия фильтров на мобильных -->
                    <button @click="showMobileFilters = !showMobileFilters"
                        class="lg:hidden w-full mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 font-medium flex items-center justify-between">
                        <span>Фильтры и настройки</span>
                        <ChevronDownIcon
                            :class="['w-5 h-5 transition-transform', showMobileFilters ? 'rotate-180' : '']" />
                    </button>

                    <!-- Панель фильтров -->
                    <div :class="['lg:block bg-white rounded-xl shadow-sm border border-gray-200 p-6',
                        showMobileFilters ? 'block' : 'hidden']">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg">Фильтры</h3>
                            <button @click="resetFilters" class="text-sm text-blue-600 hover:text-blue-800">
                                Сбросить
                            </button>
                        </div>

                        <!-- Поиск -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Поиск</label>
                            <input v-model="filters.search" type="text" class="w-full p-2 border rounded"
                                placeholder="Название материала..." @input="handleSearchInput" />
                        </div>

                        <!-- Статус -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Статус</label>
                            <div class="space-y-2">
                                <label v-for="status in statusOptions" :key="status.value"
                                    class="flex items-center cursor-pointer">
                                    <input type="radio" :checked="filters.status[0] === status.value"
                                        @change="() => toggleStatusFilter(status.value)"
                                        class="rounded text-blue-600" />
                                    <span class="ml-2">{{ status.label }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Сортировка -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Сортировка</label>
                            <select v-model="sortField" @change="handleSortSelect" class="w-full p-2 border rounded">
                                <option v-for="option in sortOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Направление сортировки -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Направление</label>
                            <div class="flex gap-2">
                                <button @click="setSortOrder('asc')"
                                    :class="['flex-1 py-2 border rounded', sortDirection === 'asc' ? 'bg-blue-50 text-blue-600 border-blue-300' : 'bg-gray-50 text-gray-700']">
                                    По возрастанию
                                </button>
                                <button @click="setSortOrder('desc')"
                                    :class="['flex-1 py-2 border rounded', sortDirection === 'desc' ? 'bg-blue-50 text-blue-600 border-blue-300' : 'bg-gray-50 text-gray-700']">
                                    По убыванию
                                </button>
                            </div>
                        </div>

                        <button @click="applyFiltersAndUpdateURL"
                            class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Применить
                        </button>

                        <div v-if="isLoading" class="mt-4 p-2 bg-blue-50 border border-blue-100 rounded-lg">
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <ArrowPathIcon class="w-4 h-4 text-blue-600 animate-spin" />
                                </div>
                                <span class="text-xs text-blue-700">Загрузка данных...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Основная область контента -->
                <div class="lg:w-3/4">
                    <!-- Десктопные табы -->
                    <div class="hidden md:flex border-b border-gray-200 mb-6">
                        <button v-for="tab in tabs" :key="tab.id" @click="handleTabChange(tab.id)" :class="['px-6 py-3 font-medium flex items-center gap-2 border-b-2 transition-colors',
                            activeTab === tab.id
                                ? 'border-blue-500 text-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700']">
                            <component :is="tab.icon" class="w-5 h-5" />
                            {{ tab.label }}
                            <span v-if="tab.count" class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                {{ tab.count }}
                            </span>
                        </button>
                    </div>

                    <!-- Информация о новой формуле конверсии -->
                    <div v-if="activeTab !== 'stats'" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="text-sm text-blue-700 flex items-start gap-2">
                            <InformationCircleIcon class="w-4 h-4 mt-0.5 flex-shrink-0" />
                            <span>
                                <strong>Конверсия рассчитывается по формуле:</strong>
                                (клики на телефон ÷ вовлечения) × 100%.
                                Показывает, какой процент вовлеченных пользователей совершил звонок.
                            </span>
                        </div>
                    </div>

                    <!-- Статистика сверху -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <!-- 1. Всего просмотров (для текущей вкладки) -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border">
                            <div class="text-sm text-gray-500">Всего просмотров</div>
                            <div class="text-2xl font-bold mt-1">{{ formatNumber(currentStats.totalViews) }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ currentStats.label }}</div>
                        </div>

                        <!-- 2. Вовлечений (для текущей вкладки) -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border">
                            <div class="text-sm text-gray-500">Вовлечений</div>
                            <div class="text-2xl font-bold mt-1 text-green-600">
                                {{ formatNumber(conversionsStats?.engaged || 0) }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <div class="flex items-center gap-1">
                                    <UserGroupIcon class="w-3 h-3" />
                                    {{ activeTab === 'articles' ? 'читателей' : 'пользователей' }}
                                </div>
                            </div>
                        </div>

                        <!-- 3. Кликов по телефону (для текущей вкладки) -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border">
                            <div class="text-sm text-gray-500">Открытий телефонов</div>
                            <div class="text-2xl font-bold mt-1 text-blue-600">
                                {{ formatNumber(conversionsStats?.phone_clicks || 0) }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <div class="flex items-center gap-1">
                                    <PhoneIcon class="w-3 h-3" />
                                    кликов
                                </div>
                            </div>
                        </div>

                        <!-- 4. Конверсия (для текущей вкладки) -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border">
                            <div class="text-sm text-gray-500">Конверсия</div>
                            <div class="text-2xl font-bold mt-1"
                                :class="getConversionRateClass(currentStats.conversionRate)">
                                {{ formatConversionRate(currentStats.conversionRate) }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ currentStats.label }}</div>
                            <div v-if="conversionsStats" class="text-xs mt-2 space-y-1">
                                <div class="flex justify-between">
                                    <span>Визитов:</span>
                                    <span>{{ formatNumber(conversionsStats.total_visits) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="flex items-center gap-1">
                                        <PhoneIcon class="w-3 h-3" />
                                        Кликов:
                                    </span>
                                    <span class="text-blue-600">{{ formatNumber(conversionsStats.phone_clicks) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="flex items-center gap-1">
                                        <UserGroupIcon class="w-3 h-3" />
                                        Вовлечено:
                                    </span>
                                    <span class="text-green-600">{{ formatNumber(conversionsStats.engaged) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Контент в зависимости от вкладки -->
                    <div v-if="activeTab === 'stats'">
                        <!-- График статистики -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="font-semibold text-lg mb-4">Динамика просмотров за последние 7 дней</h3>
                            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400">
                                <div class="text-center">
                                    <div class="mb-2 text-gray-500">Просмотры по дням:</div>
                                    <div class="text-sm font-mono text-gray-600">
                                        {{ currentStats.last7DaysViews.join(', ') || 'Нет данных' }}
                                    </div>
                                    <div class="mt-4 text-xs text-gray-500">
                                        График будет реализован с использованием Chart.js
                                    </div>
                                </div>
                            </div>

                            <!-- Детальная статистика конверсий -->
                            <div class="mt-6">
                                <h3 class="font-semibold text-lg mb-4">Статистика конверсий</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Статьи -->
                                    <div class="border rounded-lg p-4">
                                        <h4 class="font-medium text-lg mb-3 flex items-center gap-2">
                                            <DocumentTextIcon class="w-5 h-5 text-blue-500" />
                                            Статьи
                                        </h4>
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Всего визитов:</span>
                                                <span class="font-bold">{{
                                                    formatNumber(dataSource?.articles?.conversions?.total_visits || 0)
                                                }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Пользователей вовлечено:</span>
                                                <span class="font-bold text-green-600">{{
                                                    formatNumber(dataSource?.articles?.conversions?.engaged || 0)
                                                }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Кликов по телефону:</span>
                                                <span class="font-bold text-blue-600">{{
                                                    formatNumber(dataSource?.articles?.conversions?.phone_clicks || 0)
                                                }}</span>
                                            </div>
                                            <div class="flex justify-between items-center border-t pt-3">
                                                <span class="text-gray-600 font-medium">Конверсия:</span>
                                                <span class="font-bold text-lg"
                                                    :class="getConversionRateClass(dataSource?.articles?.conversions?.rate)">
                                                    {{ formatConversionRate(dataSource?.articles?.conversions?.rate) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Услуги -->
                                    <div class="border rounded-lg p-4">
                                        <h4 class="font-medium text-lg mb-3 flex items-center gap-2">
                                            <MegaphoneIcon class="w-5 h-5 text-green-500" />
                                            Объявления
                                        </h4>
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Всего визитов:</span>
                                                <span class="font-bold">{{
                                                    formatNumber(dataSource?.uslugi?.conversions?.total_visits || 0)
                                                }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Пользователей вовлечено:</span>
                                                <span class="font-bold text-green-600">{{
                                                    formatNumber(dataSource?.uslugi?.conversions?.engaged || 0)
                                                }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Кликов по телефону:</span>
                                                <span class="font-bold text-blue-600">{{
                                                    formatNumber(dataSource?.uslugi?.conversions?.phone_clicks || 0)
                                                }}</span>
                                            </div>
                                            <div class="flex justify-between items-center border-t pt-3">
                                                <span class="text-gray-600 font-medium">Конверсия:</span>
                                                <span class="font-bold text-lg"
                                                    :class="getConversionRateClass(dataSource?.uslugi?.conversions?.rate)">
                                                    {{ formatConversionRate(dataSource?.uslugi?.conversions?.rate) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Сводка -->
                                <div class="mt-6 pt-6 border-t">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                                            <div class="text-sm text-gray-600">Всего кликов</div>
                                            <div class="text-xl font-bold">
                                                {{
                                                    formatNumber((dataSource?.articles?.conversions?.phone_clicks || 0) +
                                                        (dataSource?.uslugi?.conversions?.phone_clicks || 0))
                                                }}
                                            </div>
                                        </div>
                                        <div class="text-center p-3 bg-green-50 rounded-lg">
                                            <div class="text-sm text-gray-600">Всего вовлечений</div>
                                            <div class="text-xl font-bold">
                                                {{
                                                    formatNumber((dataSource?.articles?.conversions?.engaged || 0) +
                                                        (dataSource?.uslugi?.conversions?.engaged || 0))
                                                }}
                                            </div>
                                        </div>
                                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                                            <div class="text-sm text-gray-600">Всего визитов</div>
                                            <div class="text-xl font-bold">
                                                {{
                                                    formatNumber((dataSource?.articles?.conversions?.total_visits || 0) +
                                                        (dataSource?.uslugi?.conversions?.total_visits || 0))
                                                }}
                                            </div>
                                        </div>
                                        <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                            <div class="text-sm text-gray-600">Общая конверсия</div>
                                            <div class="text-xl font-bold"
                                                :class="getConversionRateClass(dataSource?.stats?.conversionRate)">
                                                {{ formatConversionRate(dataSource?.stats?.conversionRate) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <!-- Таблица/список материалов -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Заголовок таблицы -->
                            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="font-semibold">
                                    Ваши {{ activeTab === 'articles' ? 'статьи' : 'объявления' }} ({{
                                        filteredMaterials.length }} из {{ currentStats.count }})
                                </h3>
                                <button @click="goToAddMaterial"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                                    <DocumentTextIcon class="w-4 h-4" />
                                    + Добавить {{ activeTab === 'articles' ? 'статью' : 'объявление' }}
                                </button>
                            </div>

                            <!-- На десктопе таблица -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="p-4 text-left text-sm font-medium text-gray-500">
                                                <button @click="handleSort('title')"
                                                    class="flex items-center gap-1 hover:text-gray-700">
                                                    Название
                                                    <component :is="sortArrowIcon" v-if="sortField === 'title'"
                                                        class="w-4 h-4" />
                                                </button>
                                            </th>
                                            <th class="p-4 text-left text-sm font-medium text-gray-500">
                                                <button @click="handleSort('views')"
                                                    class="flex items-center gap-1 hover:text-gray-700">
                                                    Просмотры
                                                    <component :is="sortArrowIcon" v-if="sortField === 'views'"
                                                        class="w-4 h-4" />
                                                </button>
                                            </th>
                                            <th class="p-4 text-left text-sm font-medium text-gray-500">
                                                <button @click="handleSort('date')"
                                                    class="flex items-center gap-1 hover:text-gray-700">
                                                    Дата
                                                    <component :is="sortArrowIcon" v-if="sortField === 'date'"
                                                        class="w-4 h-4" />
                                                </button>
                                            </th>
                                            <!-- Колонка конверсий -->
                                            <th class="p-4 text-left text-sm font-medium text-gray-500">
                                                <button @click="handleSort('conversions')"
                                                    class="flex items-center gap-1 hover:text-gray-700">
                                                    Конверсия
                                                    <component :is="sortArrowIcon" v-if="sortField === 'conversions'"
                                                        class="w-4 h-4" />
                                                    <InformationCircleIcon class="w-4 h-4 text-gray-400 ml-1"
                                                        title="Клики на телефон ÷ вовлечения × 100%" />
                                                </button>
                                            </th>
                                            <!-- Дополнительные колонки для статей -->
                                            <th v-if="activeTab === 'articles'"
                                                class="p-4 text-left text-sm font-medium text-gray-500">
                                                Категория
                                            </th>
                                            <!-- Дополнительные колонки для услуг -->
                                            <th v-if="activeTab === 'ads'"
                                                class="p-4 text-left text-sm font-medium text-gray-500">
                                                Город
                                            </th>
                                            <th class="p-4 text-left text-sm font-medium text-gray-500">
                                                Статус
                                            </th>
                                            <th class="p-4 text-left text-sm font-medium text-gray-500">
                                                Действия
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="material in filteredMaterials" :key="material.id"
                                            class="hover:bg-gray-50">
                                            <td class="p-4">
                                                <div class="font-medium">
                                                    <a v-if="activeTab === 'articles'"
                                                        :href="route('articles/url', [material.url])" target="_blank"
                                                        class="font-medium text-blue-600 hover:underline">{{
                                                            material.title }}</a>
                                                    <a v-if="activeTab === 'ads'"
                                                        :href="route('uslugi.url', [material.url])" target="_blank"
                                                        class="font-medium text-blue-600 hover:underline">{{
                                                            material.title }}</a>
                                                </div>
                                                <div v-if="material.conversions?.total_visits"
                                                    class="text-xs text-gray-500 mt-1">
                                                    {{ formatNumber(material.conversions.total_visits) }} визитов
                                                </div>
                                            </td>
                                            <td class="p-4">{{ formatNumber(material.views) }}</td>
                                            <td class="p-4">{{ formatDate(material.date) }}</td>
                                            <td class="p-4">
                                                <div v-if="material.conversions" class="space-y-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-lg font-bold"
                                                            :class="getConversionRateClass(material.conversions.rate)">
                                                            {{ formatConversionRate(material.conversions.rate) }}
                                                        </span>
                                                        <ArrowTrendingUpIcon class="w-4 h-4 text-gray-400" />
                                                    </div>
                                                    <div class="flex gap-3 text-xs text-gray-500">
                                                        <span class="flex items-center gap-1">
                                                            <PhoneIcon class="w-3 h-3" />
                                                            {{ formatNumber(material.conversions.phone_clicks || 0) }}
                                                            кликов
                                                        </span>
                                                        <span class="flex items-center gap-1">
                                                            <UserGroupIcon class="w-3 h-3" />
                                                            {{ formatNumber(material.conversions.engaged || 0) }}
                                                            вовлечений
                                                        </span>
                                                    </div>
                                                    <div v-if="material.conversions.engaged > 0"
                                                        class="text-xs text-gray-400">
                                                        {{ formatNumber(material.conversions.phone_clicks || 0) }} / {{
                                                            formatNumber(material.conversions.engaged || 0) }}
                                                    </div>
                                                </div>
                                                <div v-else class="text-gray-400 text-sm">
                                                    Нет данных
                                                </div>
                                            </td>
                                            <!-- Дополнительные данные для статей -->
                                            <td v-if="activeTab === 'articles'" class="p-4">
                                                <div class="flex items-center gap-1 text-sm text-gray-600">
                                                    <TagIcon class="w-4 h-4" />
                                                    {{ material.category || 'Без категории' }}
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1" v-if="material.comments_count">
                                                    {{ formatNumber(material.comments_count) }} комментариев
                                                </div>
                                            </td>
                                            <!-- Дополнительные данные для услуг -->
                                            <td v-if="activeTab === 'ads'" class="p-4">
                                                <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                                                    <MapPinIcon class="w-3 h-3" />
                                                    {{ material.city || 'не указан' }}
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                <span
                                                    :class="['px-2 py-1 text-xs rounded-full', getStatusClass(material.status)]">
                                                    {{ getStatusText(material.status) }}
                                                </span>
                                            </td>
                                            <td class="p-4">
                                                <div class="flex gap-2">
                                                    <a v-if="activeTab === 'articles'"
                                                        :href="route('articles.edit', [material.url])" target="_blank"
                                                        class="font-medium text-blue-600 hover:underline">Ред.</a>
                                                    <a v-if="activeTab === 'ads'"
                                                        :href="route('uslugi.edit', [material.url])" target="_blank"
                                                        class="font-medium text-blue-600 hover:underline">Ред.</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- На карточки мобильных  -->
                            <div class="md:hidden divide-y divide-gray-200">
                                <div v-for="material in filteredMaterials" :key="material.id" class="p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium">
                                                <a v-if="activeTab === 'articles'"
                                                    :href="route('articles/url', [material.url])" target="_blank"
                                                    class="font-medium text-blue-600 hover:underline">{{
                                                        material.title }}</a>
                                                <a v-if="activeTab === 'ads'"
                                                    :href="route('uslugi.url', [material.url])" target="_blank"
                                                    class="font-medium text-blue-600 hover:underline">{{
                                                        material.title }}</a>
                                            </h4>
                                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <EyeIcon class="w-4 h-4" />
                                                    {{ formatNumber(material.views) }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <CalendarIcon class="w-4 h-4" />
                                                    {{ formatDate(material.date) }}
                                                </span>
                                            </div>

                                            <!-- Конверсии -->
                                            <div v-if="material.conversions" class="mt-3">
                                                <div class="flex items-center gap-3 mb-1">
                                                    <ArrowTrendingUpIcon class="w-4 h-4 text-gray-400" />
                                                    <span class="font-bold"
                                                        :class="getConversionRateClass(material.conversions.rate)">
                                                        {{ formatConversionRate(material.conversions.rate) }}
                                                    </span>
                                                </div>
                                                <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <PhoneIcon class="w-3 h-3" />
                                                        {{ formatNumber(material.conversions.phone_clicks || 0) }}
                                                        кликов
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <UserGroupIcon class="w-3 h-3" />
                                                        {{ formatNumber(material.conversions.engaged || 0) }} вовлечений
                                                    </span>
                                                    <span>
                                                        {{ formatNumber(material.conversions.total_visits || 0) }}
                                                        визитов
                                                    </span>
                                                </div>
                                                <div v-if="material.conversions.engaged > 0"
                                                    class="text-xs text-gray-400 mt-1">
                                                    {{ formatNumber(material.conversions.phone_clicks || 0) }} / {{
                                                        formatNumber(material.conversions.engaged || 0) }}
                                                </div>
                                            </div>

                                            <!-- Дополнительная информация для статей -->
                                            <div v-if="activeTab === 'articles'"
                                                class="mt-2 flex flex-wrap items-center gap-2">
                                                <span class="flex items-center gap-1 text-xs text-gray-600">
                                                    <TagIcon class="w-3 h-3" />
                                                    {{ material.category || 'Без категории' }}
                                                </span>
                                                <span v-if="material.comments_count" class="text-xs text-gray-500">
                                                    {{ formatNumber(material.comments_count) }} коммент.
                                                </span>
                                            </div>

                                            <!-- Дополнительная информация для услуг -->
                                            <div v-if="activeTab === 'ads'"
                                                class="mt-2 flex flex-wrap items-center gap-2">
                                                <span class="flex items-center gap-1 text-xs text-gray-600">
                                                    <CurrencyDollarIcon class="w-3 h-3" />
                                                    {{ material.price || 'не указана' }}
                                                </span>
                                                <span class="flex items-center gap-1 text-xs text-gray-500">
                                                    <MapPinIcon class="w-3 h-3" />
                                                    {{ material.city || 'не указан' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-2">
                                            <span
                                                :class="['px-2 py-1 text-xs rounded-full', getStatusClass(material.status)]">
                                                {{ getStatusText(material.status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex gap-2">
                                        <a v-if="activeTab === 'articles'"
                                            :href="route('articles.edit', [material.url])" target="_blank"
                                            class="font-medium text-blue-600 hover:underline">Редактировать</a>
                                        <a v-if="activeTab === 'ads'" :href="route('uslugi.edit', [material.url])"
                                            target="_blank"
                                            class="font-medium text-blue-600 hover:underline">Редактировать</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Сообщение если нет данных -->
                            <div v-if="filteredMaterials.length === 0" class="p-8 text-center text-gray-500">
                                <DocumentTextIcon class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                                <p>Нет {{ activeTab === 'articles' ? 'статей' : 'объявлений' }} по выбранным фильтрам
                                </p>
                            </div>

                            <!-- Пагинация -->
                            <div v-if="filteredMaterials.length > 0 && (currentPagination?.total_pages || 0) > 1"
                                class="p-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="text-sm text-gray-500">
                                    Показано {{ filteredMaterials.length }} из {{ currentStats.count }}
                                    <span v-if="currentPagination">
                                        (Страница {{ currentPagination.current_page }} из {{
                                            currentPagination.total_pages }})
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="goToPage(currentPagination.current_page - 1)"
                                        :disabled="currentPagination.current_page <= 1"
                                        class="p-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        ←
                                    </button>
                                    <template v-for="page in paginationPages" :key="page">
                                        <button v-if="page !== '...'" @click="goToPage(page)"
                                            :class="['px-3 py-1 border rounded-lg', page === currentPagination.current_page ? 'bg-blue-50 text-blue-600 border-blue-300' : 'hover:bg-gray-50']">
                                            {{ page }}
                                        </button>
                                        <span v-else class="px-2 py-1">...</span>
                                    </template>
                                    <button @click="goToPage(currentPagination.current_page + 1)"
                                        :disabled="currentPagination.current_page >= currentPagination.total_pages"
                                        class="p-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        →
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <MainFooter />
    </div>
</template>