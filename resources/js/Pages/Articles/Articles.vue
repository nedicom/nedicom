<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

defineProps({
    articles: "Array",
});

let title = ref("Статьи");
</script>

<template>
    <Head>
        <title>{{ title }}</title>
        <meta name="description" content="Перечень статей сайта" />
    </Head>

    <MainHeader />

    <Header :ttl="title" :modalPageTitle="'статьи'"/>

    <Body>
        <div
            class="bg-white py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-sm sm:rounded-lg"
        >
            <div v-if="articles.total > 0" class="grid md:grid-cols-3 gap-9">
                <!-- card -->
                <div
                    v-for="articles in articles.data"
                    :key="articles.id"
                    class="flex justify-center mx-3 md:mx-0"
                >
                    <div
                        class="block min-w-full p-6 rounded-lg shadow-lg bg-white max-w-sm"
                    >
                        <!-- tooltip component -->
                        <div class="group flex item-center mb-2">
                            <div>
                                <Link
                                    :href="route('lawyer', articles.userid)"
                                    class="hover:underline"
                                >
                                    <img
                                        :src="
                                            'https://nedicom.ru/' +
                                            articles.avatar_path
                                        "
                                        width="40"
                                        class="rounded-full"
                                    />
                                </Link>
                            </div>

                            <div class="flex items-center justify-center">
                                <span
                                    class="h-1/2 group-hover:opacity-100 transition-opacity bg-gray-800 mx-3 px-1 text-sm text-gray-100 rounded-md opacity-0"
                                >
                                    автор -
                                    <Link
                                        :href="route('lawyer', articles.userid)"
                                        class="hover:underline"
                                        target="_blank"
                                        >{{ articles.name }}
                                    </Link>
                                </span>
                            </div>
                        </div>
                        <!-- tooltip component -->

                        <h5
                            class="text-gray-900 text-xl leading-tight line-clamp-1 font-medium h-6 mb-2"
                        >
                            {{ articles.header }}
                        </h5>

                        <p class="text-gray-700 text-xs line-clamp-3 h-12 mb-2">
                            {{ articles.description }}
                        </p>

                        <a
                            :href="route('articles/url', articles.url)"
                            class="text-blue-500 underline dark:text-blue-500 hover:no-underline"
                            >подробнее</a
                        >
                    </div>
                </div>
                <!-- card -->
            </div>

            <!-- row -->
            <div v-else class="flex justify-center h-96">
                <div class="block p-6 rounded-lg shadow-lg bg-white max-w-sm">
                    <h5
                        class="text-gray-900 text-xl leading-tight font-medium mb-2"
                    >
                        Статей пока нет
                    </h5>
                    <p class="text-gray-700 text-base mb-2">
                        Самое время
                        <a
                            :href="route('articles.add')"
                            class="basis-2/12 text-end text-blue-500 underline dark:text-blue-500 hover:no-underline"
                            >начать статью</a
                        >
                    </p>
                </div>
            </div>
            <!-- row -->

            <Pagination :links="articles.links" />
        </div>
    </Body>

    <MainFooter />

    <PopupDialogue />
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
export default {
    components: {
        Pagination,
    },
};
</script>
