<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import UslugiCard from "@/Layouts/UslugiCard.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Head } from "@inertiajs/inertia-vue3";
import PopupDialogue from "@/Layouts/PopupDialogue/PopupDialogue.vue";

let vars = defineProps({
    city: Object,
    main_usluga: Object,
    uslugi: Object,
    seconduslugi: Object,
    flash: Object,
});

</script>

<template>
    <FlashMessage :message="flash.message" />

    <Head>
        <title>{{ main_usluga.usl_name }}</title>
        <meta name="description" :content="main_usluga.usl_desc" />
    </Head>

    <MainHeader />

    <Body>
        <div>
            <Header :phone="'89788838978'" :address="null" />

            <section class="bg-white dark:bg-gray-900">
                <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-6">
                    <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
                        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">
                            Все услуги в городе {{ city.title }} в категории {{ vars.main_usluga.usl_name }}
                        </h2>
                    </div>

                    <div v-for="(usluga, index) in vars.seconduslugi">{{   }}
                        <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white text-center">
                            <a :href="route('offer.second', [city.url, vars.main_usluga.url, usluga[0].second.url])"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                {{ index }}</a>
                        </h2>
                    </div>
                </div>
            </section>

            <UslugiCard :uslugi="vars.uslugi" :city="vars.city" :main_usluga="vars.main_usluga.usl_name" />
        </div>

    </Body>

    <MainFooter />

    <PopupDialogue />
</template>