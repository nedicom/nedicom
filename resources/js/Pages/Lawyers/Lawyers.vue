<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import PromoHeader from "@/Layouts/PromoHeader.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Tracking from '@/Components/Tracking.vue';
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
    lawyers: "Array",
    auth: Object,
    backendurl: String,
});

let title = ref("Юристы");
</script>

<template>
    <Head>
        <title>{{ title }}</title>
        <meta name="description" content="Все юристы сайта nedicom.ru на этой странице" />
    </Head>

    <MainHeader :auth="auth" />

    <PromoHeader />


    <Body>
        <div class="bg-white py-12">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        v-if="lawyers.total > 0"
                        class="grid md:grid-cols-3 gap-9"
                    >
                        <!-- card -->
                        <div
                            v-for="lawyers in lawyers.data"
                            :key="lawyers.id"
                            class="flex justify-center mx-3 md:mx-0"
                        >
                            <div
                                class="block min-w-full p-6 rounded-lg shadow-lg bg-white max-w-sm"
                            >
                                <!-- tooltip component -->
                                <div class="group flex item-center mb-2">
                                    <div>
                                        <Link
                                            :href="route('lawyer', lawyers.id)"
                                            class="hover:underline"
                                        >
                                            <img
                                                v-if="lawyers.avatar_path"
                                                :src="
                                                    'https://nedicom.ru/' +
                                                    lawyers.avatar_path
                                                "
                                                width="40"
                                                class="rounded-full"
                                            />
                                        </Link>
                                    </div>

                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        <span
                                            class="h-1/2 group-hover:opacity-100 transition-opacity bg-gray-800 mx-3 px-1 text-sm text-gray-100 rounded-md opacity-0"
                                        >
                                            <Link
                                                :href="
                                                    route('lawyer', lawyers.id)
                                                "
                                                class="hover:underline"
                                                target="_blank"
                                                >подробнее
                                            </Link>
                                        </span>
                                    </div>
                                </div>
                                <!-- tooltip component -->

                                <h5
                                    class="text-gray-900 text-xl leading-tight line-clamp-2 font-medium mb-2"
                                >
                                    {{ lawyers.name }}
                                </h5>
                                <p
                                    class="text-gray-700 text-xs line-clamp-3 h-12 mb-2"
                                >
                                    {{ lawyers.about }}
                                </p>
                                <a
                                    :href="'lawyers/' + lawyers.id"
                                    class="text-blue-500 underline dark:text-blue-500 hover:no-underline"
                                    >подробнее</a
                                >
                            </div>
                        </div>
                        <!-- card -->
                    </div>

                    <!-- card -->
                    <div v-else class="flex justify-center">
                        <div
                            class="block p-6 rounded-lg shadow-lg bg-white max-w-sm"
                        >
                            <h5
                                class="text-gray-900 text-xl leading-tight font-medium mb-2"
                            >
                                Юристов пока нет
                            </h5>
                            <p class="text-gray-700 text-base mb-2">
                                Нужно кого-то зарегистрировать
                            </p>
                        </div>
                    </div>
                    <!-- card -->

                    <Pagination :links="lawyers.links" />
                </div>
            </div>
        </div>
    </Body>

    <MainFooter />

    <PopupDialogue />

    <Tracking :key="backendurl"  :tracking="$page.props.tracking" :backendurl="backendurl"/>
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
export default {
    components: {
        Pagination,
    },
};
</script>
