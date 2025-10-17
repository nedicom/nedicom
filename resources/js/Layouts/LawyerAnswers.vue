<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    anwswers: Object,
})

const answersPerPage = 10
const currentPage = ref(1)

// Вычисляемое свойство для отображения только нужного количества ответов
const displayedAnswers = computed(() => {
    if (!props.anwswers) return []
    const startIndex = 0
    const endIndex = currentPage.value * answersPerPage
    return props.anwswers.slice(startIndex, endIndex)
})

// Проверяем, есть ли еще ответы для подгрузки
const hasMoreAnswers = computed(() => {
    if (!props.anwswers) return false
    return currentPage.value * answersPerPage < props.anwswers.length
})

// Функция для подгрузки следующих ответов
const loadMore = () => {
    if (hasMoreAnswers.value) {
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
    <div class="text-center" id="answers">
        <h2 class="text-4xl font-semibold text-grey mb-4">
            Ответы юриста на вопросы
        </h2>
        <p class="text-lg text-gray-600" v-if="anwswers && anwswers.length > 0">
            Всего ответов: <span class="font-semibold text-blue-500">{{ anwswers.length }}</span>
            <span v-if="displayedAnswers.length < anwswers.length" class="text-gray-500">
                (показано: {{ displayedAnswers.length }})
            </span>
        </p>
    </div>

    <div v-if="displayedAnswers.length > 0" class="max-w-6xl mx-auto md:my-9 sm:px-6 lg:px-8 text-center">
        <div class="grid md:grid-cols-2 grid-cols-1 gap-9 mx-3 md:mx-0">
            <!-- card -->
            <div v-for="anwswer in displayedAnswers" :key="anwswer.id" class="flex justify-center">
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
                ">
                    <!-- Заголовок вопроса -->
                    <div v-if="anwswer.question && anwswer.question.title" class="mb-3">
                        <h3 class="text-lg font-semibold text-gray-800 line-clamp-3 text-left">
                            {{ anwswer.question.title }}
                        </h3>
                    </div>
                    
                    <!-- Дата создания -->
                    <div class="flex justify-between items-center mb-3 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ anwswer.created_at }}
                        </span>
                    </div>
                    
                    <!-- Текст ответа -->
                    <p class="text-gray-700 text-base line-clamp-4 h-min-24 mb-4 text-justify leading-relaxed">
                        {{ anwswer.body }}
                    </p>
                    
                    <!-- Ссылка подробнее -->
                    <div class="text-right">
                        <a v-if="anwswer.question" :href="'/questions/' + anwswer.question.url" class="
                            inline-flex
                            items-center
                            text-blue-500
                            font-medium
                            hover:text-blue-600
                            transition
                            duration-200
                        ">
                            Подробнее
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
        <div v-if="hasMoreAnswers" class="mt-8">
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
                Загрузить еще ответы
            </button>
        </div>
    </div>
    
    <div v-else class="text-center pb-20 text-gray-500 text-lg">
        Юрист еще не отвечал пользователям
    </div>
    <!-- case card -->
</template>