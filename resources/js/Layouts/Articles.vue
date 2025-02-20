<script setup>
const props = defineProps({
    articles: "Array",
});
</script>

<template>
    <!-- case card -->
    <h2 class="text-4xl font-semibold text-grey text-center py-10" id="posts">
        Публикации юриста
    </h2>

    <div v-if="articles[0]" class="max-w-6xl mx-auto md:my-9 sm:px-6 lg:px-8 text-center">

        <div class="grid md:grid-cols-3 grid-cols-1 gap-9 mx-3 md:mx-0">

            <!-- card -->
            <div v-for="article in articles" :key="article.id" class="flex justify-center">
                <div class="
                    block
                    min-w-full
                    p-6
                    rounded-lg
                    shadow-lg
                    bg-white
                    max-w-sm
                    ">
                    <h5 class="
                text-gray-900 text-xl
                leading-tight
                line-clamp-1
                font-medium
                mb-2
            ">
                        {{ article.header }}
                    </h5>
                    <p class="text-gray-700 text-base line-clamp-3 h-min-24 mb-2">
                        {{ article.description }}
                    </p>
                    <a :href="'/articles/' + article.url" class="
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
    <div v-else class="text-center">
        юрист еще не подготовил ни одной публикации
    </div>
    <!-- case card -->
</template>