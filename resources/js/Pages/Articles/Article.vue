<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Chat from "@/Layouts/Chat.vue";
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

    <Header :ttl="vars.article.header" :modalPageTitle="'статья - ' + vars.article.header" />

    <Body>
        <div class="flex justify-center  text-gray-900" itemscope itemtype="https://schema.org/Article">
            <div class="py-6 md:w-4/6">
                <div class="max-w-5xl sm:px-6 lg:px-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="md:py-12">
                            <div class="mx-auto sm:px-6 lg:px-8">
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
                                    <div class="group flex justify-between mb-2">
                                        <div class="group flex item-center">
                                            <div class="flex items-center justify-center relative">
                                                <Link :href="route('lawyer', article.userid)
                                                    " class="hover:underline">
                                                <img :src="'https://nedicom.ru/' +
                                                    article.avatar_path
                                                    " width="40" class="rounded-full" />
                                                <span
                                                    class="-left-1 -top-1 animate-pulse absolute w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
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
                                                        " class="hover:underline"> <span itemprop="name">{{
                                                            article.name }}</span>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-center text-xs md:text-sm">
                                            <div class="mr-3" itemprop="datePublished" :content="article.created_at">
                                                <div class="md:block hidden">создано:</div>
                                                <div>{{ article.created }}</div>
                                            </div>

                                            <div class="md:block hidden" itemprop="dateModified"
                                                :content="article.updated_at">
                                                <div class="md:block hidden">обновлено:</div>
                                                <div>{{ article.updated }} </div>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- tooltip component -->


                                    <section class="bg-white dark:bg-gray-900 visible md:hidden">
                                        <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                                            role="alert">
                                            <span class="font-medium">Ванимание.</span> Автор статьи сейчас онлайн.
                                        </div>
                                        <div class="px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
                                            <div class="max-w-screen-md">
                                                <div
                                                    class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                                                    <a :href="route('lawyer.message', [article.userid])"
                                                        class="inline-flex items-center justify-center px-4 py-2.5 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-600">

                                                        Чат с автором
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <div v-if="vars.article.header" itemprop="headline"
                                        class="my-4 text-3xl font-extrabold leading-tight lg:mb-6 lg:text-4xl dark:text-white lead">
                                        {{ vars.article.header }}
                                    </div>

                                    <div v-if="usluga" itemprop="headline" class="my-4">
                                        Категория юриста: <a :href="'https://nedicom.ru/uslugi/simferopol/' + usluga.newurl"
                                            class="font-bold hover:underline">{{ usluga.usl_name }}</a>
                                    </div>

                                    <div v-if="article.description" class="my-3" itemprop="description">
                                        {{ article.description }}
                                    </div>

                                    <div v-if="article.youtube_file_path" class="my-6">
                                        <iframe width="100%" height="500" :src="article.youtube_file_path"
                                            loading="lazy" title="YouTube video player" frameborder="0"
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
            <Chat :user="vars.article" :usluga="vars.usluga" class="hidden md:block" />
        </div>
    </Body>



    <!-- <Sidebaraction :ModalBtnText="ModalBtnText" /> -->
    <MainFooter />

</template>
