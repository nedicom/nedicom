<script setup>
import Modal from "@/Components/Modal.vue";
import ModalPhone from "@/Components/ModalPhone.vue";
import WaBtn from "@/Components/WaBtn.vue";

let address = "Респ. Крым, г. Симферополь, ул. Долгоруковская, 5";
let phone = "+7 978 8838 978";

const props = defineProps({
    usl_name: String,
    address: String,
    dopadress: String,
    phone: String,
    maps: String,
    metaimage: String,
    company: String
});

if (props.address !== undefined) {
    address = props.address;
}
if (props.phone !== undefined) {
    phone = props.phone;
}
</script>

<template>
    <!-- address -->
    <div itemscope itemtype="https://schema.org/Organization" class="md:flex justify-center md:my-20 p-5 md:p-0">
        <div class="md:w-1/2 w-full">
            <section>
                <div class="px-4 mx-auto max-w-screen-xl">
                    <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"
                        class="my-12 md:my-10 md:mt-0 text-center">
                        <p v-if="dopadress" class="font-semibold text-3xl">Главный офис:</p>
                        <p v-else class="font-semibold text-3xl">Адрес:</p>
                        <h2 class="mb-4 text-xl my-6 font-semibold">{{ address }}</h2>

                        <meta itemprop="postalCode" content="295000" />
                        <meta itemprop="addressLocality" content="Russia" />
                        <meta itemprop="iso6523Code" content="317910200050560" />
                        <meta itemprop="iso6523Code" content="910305709385" />

                        <p v-if="dopadress" class="font-semibold text-3xl mt-6">Дополнительные офисы</p>
                        <h2 v-if="dopadress" v-html="dopadress" class="mb-4 text-xl my-6 font-semibold"></h2>

                    </div>
                    <div class="flex justify-center">

                        <div class="mt-5 md:w-1/2 font-bold">
                            <p class="mb-6 text-center">Выбирайте удобный способ
                                связи для Вас
                            </p>

                            <div class="w-full flex items-center mb-5">
                                <Modal :ModalBtnText="'Обратный звонок'" :secondtext=null />
                            </div>

                            <div class="w-full flex items-center mb-5">
                                <ModalPhone :phone="phone" :lawyer="'Обратиться к юристу'" :phoneto="'tel:' + phone"
                                    :avatarPath=null />
                            </div>

                            <div class="w-full flex items-center mb-5">
                                <WaBtn :phone="phone" />
                            </div>

                            <div v-if="props.maps" class="w-full flex items-center mb-5">
                                <a :href="props.maps" onclick="ym(24900584, 'reachGoal', 'YaMap_Click'); return true;" class="w-64 mx-auto inline-flex items-center justify-center py-2.5 text-gray-900 focus:outline-none 
                                    bg-white rounded-lg border border-gray-200 inline-flex items-center focus:ring-4 focus:ring-primary-300 
                                    font-medium rounded-lg px-3 py-2.5 text-center" target="_blank">
                                    <span class="h-full mr-1"><img class="h-6"
                                            src="https://yandex.ru/maps/favicon.svg"></span>
                                    <span class="font-bold h-full mr-1">
                                        <span class="text-red-600">Я</span>ндекс карты 
                                    </span>
                                    <span v-for="x in 5" :key="x">
                                        <svg class="w-4 h-4 text-yellow-300" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                            <path
                                                d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                        </svg>
                                    </span>
                                </a>
                            </div>

                            <p itemprop="name" class="text-center mt-6">{{ props.company }}</p>
                            <meta itemprop="image" :content="metaimage" />
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
</template>
