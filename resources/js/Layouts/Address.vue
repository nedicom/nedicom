<script setup>
let address = "Респ. Крым, г. Симферополь, ул. Долгоруковская, 5";
let phone = "+7 978 8838 978";

const props = defineProps({
    usl_name: String,
    address: String,
    phone: String,
    maps: String,
});

if (props.address !== undefined) {
    address = props.address;
}
if (props.phone !== undefined) {
    phone = props.phone;
}

let phoneto = "tel:" + phone;

</script>

<template>
    <h1 class="text-4xl font-semibold text-grey text-center mt-12 mb-6">
        Контакты
    </h1>

    <div itemprop="name" class="text-4xl mb-12 px-6 font-semibold text-grey text-center">
        {{ props.usl_name }}
    </div>

    <!-- address -->
    <div class="md:flex justify-center">
        <div v-if="props.maps" class="md:w-1/2 w-full mt-6" style="height:400px">
            <iframe :src="props.maps" width="100%" height="400" frameborder="0">
            </iframe>
        </div>

        <div class="md:w-1/2 w-full px-3 flex flex-col justify-center gap-7" style="height:400px">

            <div>
                <div class="text-3xl font-semibold text-grey text-center">
                    <a :href="phoneto">{{ phone }}</a>
                </div>
            </div>

            <div class="flex items-center justify-center">
                <a href="tel:+79788838978">
                    <img src="https://nedicom.ru/storage/images/landing/main/icons8-iphone-se.gif">
                </a>
            </div>

            <div>
                <h4 class="text-2xl font-semibold text-grey text-center">Записаться на консультацию</h4>
            </div>

            <div v-if="props.maps" class="flex items-center justify-center">
                <a href="https://yandex.ru/maps/-/CCUkY0bIwD">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48" height="48" viewBox="0 0 48 48">
                        <linearGradient id="iu22Zjf0u3e5Ts0QLZZhJa_uzeKRJIGwbBY_gr1" x1="11.274" x2="36.726" y1="9.271"
                            y2="34.723" gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-color="#d43a02"></stop>
                            <stop offset="1" stop-color="#b9360c"></stop>
                        </linearGradient>
                        <path fill="url(#iu22Zjf0u3e5Ts0QLZZhJa_uzeKRJIGwbBY_gr1)"
                            d="M36.902,34.536C40.052,31.294,42,26.877,42,22c0-9.94-8.06-18-18-18S6,12.06,6,22	c0,4.877,1.948,9.294,5.098,12.536c0.018,0.019,0.03,0.04,0.048,0.059l0.059,0.059c0.047,0.048,0.094,0.095,0.142,0.142	l11.239,11.239c0.781,0.781,2.047,0.781,2.828,0l11.239-11.239c0.048-0.047,0.095-0.094,0.142-0.142l0.059-0.059	C36.873,34.576,36.885,34.554,36.902,34.536z">
                        </path>
                        <radialGradient id="iu22Zjf0u3e5Ts0QLZZhJb" cx="24" cy="22.5" r="9.5"
                            gradientUnits="userSpaceOnUse">
                            <stop offset=".177"></stop>
                            <stop offset="1" stop-opacity="0"></stop>
                        </radialGradient>
                        <circle cx="24" cy="22.5" r="9.5" fill="url(#undefined)"></circle>
                        <circle cx="24" cy="22" r="8" fill="#f9f9f9"></circle>
                        <radialGradient id="iu22Zjf0u3e5Ts0QLZZhJc" cx="23.842" cy="43.905" r="13.637"
                            gradientUnits="userSpaceOnUse">
                            <stop offset=".177"></stop>
                            <stop offset="1" stop-opacity="0"></stop>
                        </radialGradient>
                        <path fill="url(#undefined)"
                            d="M24,30c-4.747,0-8.935,2.368-11.467,5.982l10.052,10.052c0.781,0.781,2.047,0.781,2.828,0	l10.052-10.052C32.935,32.368,28.747,30,24,30z">
                        </path>
                    <path fill="#de490d"
                        d="M24,32c-4.196,0-7.884,2.157-10.029,5.42l8.615,8.615c0.781,0.781,2.047,0.781,2.828,0l8.615-8.615	C31.884,34.157,28.196,32,24,32z">
                    </path>
                </svg>
            </a>
        </div>

        <div>
            <div class="text-2xl font-semibold text-grey text-center">
                <a href="">
                    {{ address }}
                </a>
            </div>
        </div>
    </div>


    <!-- address -->
</div></template>
