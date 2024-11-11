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
                        class="my-12 md:my-20 md:mt-0 text-center">
                        <p v-if="dopadress" class="font-semibold text-3xl">Главный офис:</p>
                        <p v-else class="font-semibold text-3xl">Наш офис:</p>
                        <h2 class="mb-4 text-xl my-6 font-semibold">{{ address }}</h2>

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
                                <a :href="props.maps" class="w-64 mx-auto inline-flex items-center justify-center py-2.5 text-gray-900 focus:outline-none 
                                    bg-white rounded-lg border border-gray-200 inline-flex items-center focus:ring-4 focus:ring-primary-300 
                                    font-medium rounded-lg px-5 py-2.5 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    <span class="flex flex-col">
                                        Яндекс карты    
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
