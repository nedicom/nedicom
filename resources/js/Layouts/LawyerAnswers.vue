<script setup>
const props = defineProps({
    anwswers: Object,
});
</script>

<template>
    <!-- case card -->
    <h2 class="text-4xl font-semibold text-grey text-center py-10" id="answers">
        Ответы юриста на вопросы
    </h2>

    <div v-if="anwswers[0]" class="max-w-6xl mx-auto md:my-9 sm:px-6 lg:px-8 text-center">

        <div class="grid md:grid-cols-3 grid-cols-1 gap-9 mx-3 md:mx-0">

            <!-- card -->
            <div v-for="anwswer in anwswers" :key="anwswer.id" class="flex justify-center">
                <div class="
                    block
                    min-w-full
                    p-6
                    rounded-lg
                    shadow-lg
                    bg-white
                    max-w-sm
                    ">
                    <p class="text-gray-700 text-base line-clamp-5 h-min-24 mb-2">
                        {{ anwswer.body }}
                    </p>
                    <a v-if="anwswer.question" :href="'/questions/' + anwswer.question.url" class="
                text-blue-500
                underline
                dark:text-blue-500
                hover:no-underline
            ">подробнее</a>
                </div>
            </div>
            <!-- card -->
        </div>


    </div>
    <div v-else class="text-center pb-20">
        юрист еще не отвечал пользователям
    </div>
    <!-- case card -->
</template>