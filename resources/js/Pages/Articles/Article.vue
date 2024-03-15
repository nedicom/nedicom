<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import Sidebaraction from "@/Layouts/Sidebaraction.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Modal from "@/Components/Modal.vue";
import TailwindModal from "@/Components/TailwindModal.vue";
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

let vars = defineProps({
    article: "Object",
    user: "Object",
});

</script>

<style>
.article h3 {
    font-size: 2rem;
}

.article ul,
ol {
    padding: 0 1rem;
    margin-left: 1rem;
    list-style-type: square;
}
</style>

<template>

    <Head>
        <title>{{ article.header }}</title>
        <meta name="description" :content="article.description" />
    </Head>

    <MainHeader />

    <Header :ttl="article.header" />

    <Body>

        <div class="py-6 md:w-4/6 flex justify-start">
            <div class="max-w-5xl sm:px-6 lg:px-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-12">
                        <div class="xl:w-4/6 mx-auto sm:px-6 lg:px-8">
                            <div class="px-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div v-if="vars.user" class="my-3">
                                    <div v-if="vars.user.id == article.userid">
                                        <a :href="route(
            'articles.edit',
            article.url
        )
            "
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Редактировать</a>
                                    </div>
                                </div>

                                <!-- tooltip component -->
                                <div class="group flex item-center mb-2">
                                    <div>
                                        <Link :href="route('lawyer', article.userid)
            " class="hover:underline">
                                        <img :src="'https://nedicom.ru/' +
            article.avatar_path
            " width="40" class="rounded-full" />
                                        </Link>
                                    </div>

                                    <div class="flex items-center justify-center">
                                        <span
                                            class="h-1/2 group-hover:opacity-100 transition-opacity bg-gray-800 mx-3 px-1 text-sm text-gray-100 rounded-md opacity-0">
                                            автор -
                                            <Link :href="route(
            'lawyer',
            article.userid
        )
            " class="hover:underline">{{ article.name }}
                                            </Link>
                                        </span>
                                    </div>
                                </div>
                                <!-- tooltip component -->

                                <div v-if="article.header"
                                    class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl dark:text-white">
                                    {{ article.header }}
                                </div>

                                <div v-if="article.description" class="my-3">
                                    {{ article.description }}
                                </div>

                                <div v-if="article.youtube_file_path" class="aspect-w-16 aspect-h-9">
                                    <iframe width="560" height="315" :src="article.youtube_file_path"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen></iframe>
                                </div>

                                <div v-html="article.body"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Body>

    <!-- <Sidebaraction :ModalBtnText="ModalBtnText" /> -->
    <MainFooter />
</template>
