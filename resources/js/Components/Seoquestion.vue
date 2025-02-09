<script setup>
defineProps({
    questiondata: Object,
});
</script>

<template>
    <section v-if="questiondata.popular_question">
        <!-- popular question -->

        <div v-if="questiondata" id="questions" class="my-12 pb-12" itemscope itemtype="https://schema.org/FAQPage">
            <h3 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 text-center">
                Что нужно знать перед обращением к юристу
            </h3>
            <ul
                class="md:w-4/6 w-11/12 mx-auto mt-10 md:mt-20 divide-y shadow-gray-600 shadow-2xl rounded-xl list-none">
                <li v-for="item in questiondata.popular_question" :key="item" class="py-5" itemscope
                    itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <details class="group">
                        <summary
                            class="flex items-center gap-3 px-4 py-3 font-medium marker:content-none hover:cursor-pointer">
                            <svg class="w-5 h-5 text-gray-500 transition group-open:rotate-90"
                                xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                                </path>
                            </svg>
                            <h4 itemprop="name" class="font-bold text-gray-700 sm:text-lg">{{ item.question }}</h4>
                        </summary>

                        <article class="px-4 pb-4 font-light text-gray-500 sm:text-lg" itemscope itemprop="acceptedAnswer"
                            itemtype="https://schema.org/Answer">
                            <p itemprop="text">
                                {{ item.answer }}
                            </p>
                        </article>
                    </details>
                </li>
            </ul>
        </div>
        <!-- popular question -->
    </section>
</template>
