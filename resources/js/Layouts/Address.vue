<script setup>
import Modal from "@/Components/Modal.vue";

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
                    <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" class="my-12 md:my-20 md:mt-0 text-center">
                        <p v-if="dopadress" class="font-semibold text-3xl">Главный офис:</p>
                        <p v-else class="font-semibold text-3xl">Наш офис:</p>
                        <h2  class="mb-4 text-xl my-6 font-semibold">{{ address }}</h2>
                        
                        <meta itemprop="postalCode" content="295000" />
                        <meta itemprop="addressLocality" content="Russia" />
                        <meta itemprop="iso6523Code" content="317910200050560" />
                        <meta itemprop="iso6523Code" content="910305709385" />

                        <p v-if="dopadress" class="font-semibold text-3xl mt-6">Дополнительные офисы</p>
                        <h2 v-if="dopadress" v-html="dopadress" class="mb-4 text-xl my-6 font-semibold"></h2>

                    </div>
                    <div class="flex justify-center">

                        <div class="mt-5 md:w-1/2 ">
                            <p class="mb-6 text-center">Выбирайте удобный способ
                                связи для Вас
                            </p>

                            <Modal :ModalBtnText="'записаться'" :secondtext="'на консультацию'" />

                            <a :href="'tel:' + props.phone" type="button" class="my-5 w-full inline-flex items-center justify-center text-white mr-2 bg-blue-600 hover:bg-blue-700                     
    font-medium rounded-lg py-2.5
  ">
                                <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                                <span class="flex flex-col">
                                    <span itemprop="telephone">
                                        {{ props.phone }}
                                    </span>
                                    <span class="text-xs">
                                        обратиться по телефону
                                    </span>
                                </span>
                            </a>

                            <a :href="'https://wa.me/' + props.phone.replace(/[^-0-9-.]/, '') + '?text=Здравствуйте.'"
                                type="button" class="mb-5 w-full inline-flex items-center justify-center text-white mr-2 bg-emerald-700 hover:bg-emerald-800                     
    font-medium rounded-lg py-2.5
  ">
                                <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>

                                <span class="flex flex-col">
                                    <span class="">
                                        {{ props.phone }}
                                    </span>
                                    <span class="text-xs">
                                        написать в whatsapp
                                    </span>
                                </span>
                            </a>

                            <a v-if="props.maps" :href="props.maps"
                                class="w-full inline-flex items-center justify-center py-2.5 mr-2 text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 inline-flex items-center focus:ring-4 focus:ring-primary-300 font-medium rounded-lg px-5 py-2.5 text-center dark:focus:ring-primary-900">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <span class="flex flex-col">


                                    проложить маршрут

                                    <span class="text-xs">
                                        на картах
                                    </span>
                                </span>
                            </a>
                            <p itemprop="name" class="text-center mt-6">{{ props.company }}</p>
                            <meta itemprop="image" :content="metaimage" />
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
</template>
