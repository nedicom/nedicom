<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    articles: Array,
})

const articlesPerPage = 8
const currentPage = ref(1)

// Вычисляемое свойство для отображения только нужного количества статей
const displayedArticles = computed(() => {
    if (!props.articles) return []
    const startIndex = 0
    const endIndex = currentPage.value * articlesPerPage
    return props.articles.slice(startIndex, endIndex)
})

// Проверяем, есть ли еще статьи для подгрузки
const hasMoreArticles = computed(() => {
    if (!props.articles) return false
    return currentPage.value * articlesPerPage < props.articles.length
})

// Функция для подгрузки следующих статей
const loadMore = () => {
    if (hasMoreArticles.value) {
        currentPage.value++
    }
}

// Функция для форматирования даты
const formatDate = (dateString) => {
    if (!dateString) return ''
    
    const date = new Date(dateString)
    const now = new Date()
    const diffTime = Math.abs(now - date)
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24))
    
    // Если сегодня
    if (diffDays === 0) {
        return 'Сегодня'
    }
    // Если вчера
    if (diffDays === 1) {
        return 'Вчера'
    }
    // Если меньше недели
    if (diffDays < 7) {
        return `${diffDays} дней назад`
    }
    
    // Форматируем полную дату
    return date.toLocaleDateString('ru-RU', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
}
</script>

<template>
    <!-- case card -->
    <div class="text-center mt-10" id="posts">
        <h2 class="text-4xl font-semibold text-grey mb-4">
            Публикации юриста
        </h2>
        <p class="text-lg text-gray-600" v-if="articles && articles.length > 0">
            Всего публикаций: <span class="font-semibold text-blue-500">{{ articles.length }}</span>
            <span v-if="displayedArticles.length < articles.length" class="text-gray-500">
                (показано: {{ displayedArticles.length }})
            </span>
        </p>
    </div>

    <div v-if="displayedArticles.length > 0" class="max-w-6xl mx-auto md:my-9 sm:px-6 lg:px-8 text-center">
        <div class="grid md:grid-cols-2 grid-cols-1 gap-9 mx-3 md:mx-0">
            <!-- card -->
            <div v-for="article in displayedArticles" :key="article.id" class="flex justify-center">
                <div class="
                    block
                    min-w-full
                    p-6
                    rounded-lg
                    shadow-lg
                    bg-white
                    max-w-sm
                    hover:shadow-xl
                    transition-shadow
                    duration-300
                    h-full
                    flex
                    flex-col
                ">
                    <!-- Заголовок статьи -->
                    <h5 class="
                        text-gray-900
                        text-xl
                        leading-tight
                        line-clamp-2
                        font-semibold
                        mb-3
                        text-left
                        flex-grow-0
                    ">
                        {{ article.header }}
                    </h5>
                    
                    <!-- Дата создания -->
                    <div class="flex justify-start items-center mb-3 text-sm text-gray-500 flex-grow-0">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ article.created_at }}
                        </span>
                    </div>
                    
                    <!-- Описание статьи -->
                    <p class="text-gray-700 text-base line-clamp-4 mb-4 text-justify leading-relaxed flex-grow">
                        {{ article.description }}
                    </p>
                    
                    <!-- Ссылка подробнее -->
                    <div class="text-right mt-auto pt-3">
                        <a :href="'/articles/' + article.url" class="
                            inline-flex
                            items-center
                            text-blue-500
                            font-medium
                            hover:text-blue-600
                            transition
                            duration-200
                        ">
                            Читать статью
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <!-- card -->
        </div>

        <!-- Кнопка подгрузки -->
        <div v-if="hasMoreArticles" class="mt-8">
            <button 
                @click="loadMore"
                class="
                    px-8
                    py-3
                    bg-blue-500
                    text-white
                    font-medium
                    rounded-lg
                    hover:bg-blue-600
                    transition
                    duration-200
                    focus:outline-none
                    focus:ring-2
                    focus:ring-blue-300
                    focus:ring-opacity-50
                    shadow-md
                    hover:shadow-lg
                "
            >
                Загрузить еще публикации
            </button>
        </div>
    </div>
    
    <div v-else class="text-center pb-20 text-gray-500 text-lg">
        Юрист еще не подготовил ни одной публикации
    </div>
    <!-- case card -->
</template>