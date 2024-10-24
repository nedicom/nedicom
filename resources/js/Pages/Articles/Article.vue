<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Chat from "@/Layouts/Chat.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { ref, onMounted } from "vue";

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
        <div class="flex justify-center text-gray-900" itemscope itemtype="https://schema.org/Article">
            <div class="py-6 md:w-4/6">
                <div class="max-w-5xl sm:px-6 lg:px-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="md:py-12">
                            <div class="mx-auto sm:px-6 lg:px-8">
                                <div class="px-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div v-if="vars.user" class="my-3">
                                        <div v-if="vars.user.id == article.userid || vars.user.is_admin == 1">
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
                                                    "
                                                    :alt="usluga.usl_name" 
                                                    width="40" class="rounded-full" />
                                                <span
                                                    class="-left-1 -top-1 animate-pulse absolute w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                                                </Link>
                                            </div>

                                            <div class="flex items-center justify-center" itemprop="author" itemscope
                                                itemtype="https://schema.org/Person">
                                                <span
                                                    class="md:h-1/2 group-hover:opacity-100 transition-opacity bg-gray-800 mx-3 px-1 text-sm text-gray-100 rounded-md md:opacity-0">
                                                    автор -
                                                    <a itemprop="url" :href="route(
                                                        'lawyer',
                                                        article.userid
                                                    )
                                                        " 
                                                        :aria-label="'автор статьи - ' + article.name"
                                                        class="hover:underline"> <span itemprop="name">{{
                                                            article.name }}</span>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mr-2 my-5 md:my-0 flex">
                                            <a type="button"
                                            aria-label="поделиться статьей в социальной сети ВКонтакте"
                                                :href="'https://vk.com/share.php?url=https://nedicom.ru/articles/' + vars.article.url + '&image=https://nedicom.ru/' + vars.article.avatar_path + '&title=Интересная статья с nedicom.ru. ' + vars.article.header"
                                                target="_blank" class="mr-1 inline-flex items-center">
                                                <svg class="w-6 h-6 md:w-12 md:h-12 md:mr-1 fill-cyan-500 hover:fill-cyan-700"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M15.07 2H8.93C3.33 2 2 3.33 2 8.93v6.14C2 20.67 3.33 22 8.93 22h6.14c5.6 0 6.93-1.33 6.93-6.93V8.93C22 3.33 20.67 2 15.07 2m3.08 14.27h-1.46c-.55 0-.72-.45-1.69-1.44c-.88-.83-1.26-.95-1.47-.95c-.29 0-.38.08-.38.5v1.31c0 .35-.11.57-1.04.57c-1.54 0-3.25-.94-4.45-2.67c-1.81-2.54-2.3-4.46-2.3-4.84c0-.21.07-.41.49-.41h1.47c.37 0 .51.16.65.56c.72 2.1 1.92 3.9 2.41 3.9c.19 0 .27-.09.27-.55V10.1c-.05-.98-.58-1.07-.58-1.42c0-.18.14-.34.37-.34h2.29c.31 0 .42.16.42.54v2.89c0 .31.13.42.23.42c.18 0 .34-.11.67-.45c1.05-1.17 1.8-2.98 1.8-2.98c.1-.21.26-.41.65-.41h1.43c.44 0 .54.23.44.54c-.18.85-1.96 3.36-1.94 3.36c-.16.25-.22.36 0 .65c.15.21.66.65 1 1.04c.62.71 1.1 1.3 1.23 1.71c.11.41-.09.62-.51.62z" />
                                                </svg>
                                            </a>
                                            <a type="button"
                                                aria-label="поделиться статьей в социальной сети Одноклассники"
                                                :href="'https://connect.ok.ru/offer?url=https://nedicom.ru/articles/' + vars.article.url + '&title=Интересная статья с nedicom.ru. ' + vars.article.header + '&imageUrl=https://nedicom.ru/' + vars.article.avatar_path"
                                                target="_blank" class="inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                                    class="w-6 h-6 md:w-12 md:h-12 fill-amber-500 hover:fill-amber-600"
                                                    viewBox="0 0 48 48">
                                                    <path
                                                        d="M42,37c0,2.8-2.2,5-5,5H11c-2.8,0-5-2.2-5-5V11c0-2.8,2.2-5,5-5h26c2.8,0,5,2.2,5,5V37z">
                                                    </path>
                                                    <path fill="#FFF"
                                                        d="M26.9,30.4c1.5-0.3,2.9-0.9,4.1-1.7c1-0.6,1.3-1.9,0.7-2.9c-0.6-1-1.9-1.3-2.9-0.7c-2.9,1.8-6.7,1.8-9.6,0c-1-0.6-2.3-0.3-2.9,0.7c-0.6,1-0.3,2.3,0.7,2.9c1.3,0.8,2.7,1.4,4.1,1.7l-4,4c-0.8,0.8-0.8,2.1,0,3c0.4,0.4,0.9,0.6,1.5,0.6c0.5,0,1.1-0.2,1.5-0.6l3.9-3.9l3.9,3.9c0.8,0.8,2.1,0.8,3,0c0.8-0.8,0.8-2.1,0-3C30.9,34.4,26.9,30.4,26.9,30.4z M24,10c-3.9,0-7,3.1-7,7c0,3.9,3.1,7,7,7c3.9,0,7-3.1,7-7C31,13.1,27.9,10,24,10z M24,20c-1.7,0-3-1.3-3-3c0-1.7,1.3-3,3-3c1.7,0,3,1.3,3,3C27,18.7,25.7,20,24,20z">
                                                    </path>
                                                </svg>
                                            </a>
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

                                    <div v-if="usluga" class="my-4">
                                        Категория юриста: <a
                                            :href="'https://nedicom.ru/uslugi/simferopol/' + usluga.newurl"
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
                                    <div v-if="vars.article.practice_file_path"
                                        class="float-none md:float-right w-full md:w-1/2 mx-1 md:ml-10 rounded-lg">
                                        <img rel="preload" fetchpriority="high" itemprop="image" class="transition-all duration-300 blur-sm hover:blur-none border border-2 border-gray-600 rounded-lg shadow-lg "
                                            :src="'https://nedicom.ru/' + vars.article.practice_file_path"
                                            :alt="usluga.usl_name + ' - практика'" >
                                            <div class="text-sm font-bold text-center mt-3">{{ usluga.usl_name }} - практика</div>
                                    </div>
                                    <div class="text-justify" v-html="article.body" itemprop="text"></div>
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
