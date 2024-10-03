<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import Sidebaraction from "@/Layouts/Sidebaraction.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Modal from "@/Components/Modal.vue";
import TailwindModal from "@/Components/TailwindModal.vue";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

let vars = defineProps({
    article: "Object",
    user: "Object",
    usluga: "Object",
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

    <Head title="{{ vars.article.header }}">
        <title>{{ vars.article.header }}</title>
        <meta name="description" :content="article.description" />
    </Head>

    <MainHeader />

    <Header :ttl="vars.article.header" />

    <Body>
        <div class="flex justify-center  text-gray-900" itemscope itemtype="https://schema.org/Article">
            <div class="py-6 md:w-4/6">
                <div class="max-w-5xl sm:px-6 lg:px-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="py-12">
                            <div class="mx-auto sm:px-6 lg:px-8">
                                <div class="px-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div v-if="vars.user" class="my-3">
                                        <div v-if="vars.user.id == article.userid">
                                            <a :href="route(
            'articles.edit',
            article.url
        )
            " class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Редактировать</a>
                                        </div>
                                    </div>

                                    <!-- tooltip component -->
                                    <div class="group flex justify-between mb-2">
                                        <div class="group flex item-center">
                                            <div class="flex items-center justify-center">
                                                <Link :href="route('lawyer', article.userid)
                                                    " class="hover:underline">
                                                        <img :src="'https://nedicom.ru/' +
                                                    article.avatar_path
                                                    " width="40" class="rounded-full" />
                                                </Link>
                                            </div>

                                            <div class="flex items-center justify-center" itemprop="author" itemscope
                                                itemtype="https://schema.org/Person">
                                                <span
                                                    class="h-1/2 group-hover:opacity-100 transition-opacity bg-gray-800 mx-3 px-1 text-sm text-gray-100 rounded-md md:opacity-0">
                                                    автор -
                                                    <a itemprop="url" :href="route(
                                                            'lawyer',
                                                            article.userid
                                                        )
                                                            " class="hover:underline"> <span itemprop="name">{{ article.name }}</span>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-center text-xs md:text-sm">
                                            <div  class="mr-3" itemprop="datePublished" :content="article.created_at">
                                                <div class="md:block hidden">создано:</div>  <div>{{ article.created }}</div>
                                            </div>
                                            
                                            <div class="md:block hidden" itemprop="dateModified" :content="article.updated_at">
                                                <div class="md:block hidden">обновлено:</div>  <div>{{ article.updated }} </div>
                                            </div>
                                           
                                        </div>
                                    </div>

                                    <!-- tooltip component -->

                                    <div v-if="vars.article.header" itemprop="headline"
                                        class="my-4 text-3xl font-extrabold leading-tight lg:mb-6 lg:text-4xl dark:text-white lead">
                                        {{ vars.article.header }}
                                    </div>

                                    <div v-if="usluga" itemprop="headline"
                                        class="my-4">
                                        Категория: <a :href="'https://nedicom.ru/uslugi/'+usluga.newurl"  class="font-bold hover:underline">{{usluga.usl_name}}</a>
                                    </div>

                                    <div v-if="article.description" class="my-3" itemprop="description">
                                        {{ article.description }}
                                    </div>

                                    <div v-if="article.youtube_file_path" class="my-6"  >
                                        <iframe width="100%" height="500" :src="article.youtube_file_path" loading="lazy"
                                            title="YouTube video player" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen></iframe>
                                    </div>

                                    <div v-html="article.body" itemprop="text"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Body>

    <!-- <Sidebaraction :ModalBtnText="ModalBtnText" /> -->
    <MainFooter />

    <PopupDialogue />
</template>
