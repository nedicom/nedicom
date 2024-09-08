<script setup>
import Modal from "@/Components/Modal.vue";

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
    <!-- address -->
    <div class="md:flex justify-center mt-20 md:my-20 p-5 md:p-0">
        <div v-if="props.maps" class="md:w-1/2 w-full " style="height:400px">
            <iframe :src="props.maps" width="100%" height="400" frameborder="0">
            </iframe>
        </div>

        <div class="md:w-1/2 w-full px-3 flex flex-col justify-center text-md" style="height:400px">

            <div class="text-center flex flex-col md:flex-row justify-center">
                <a :href="'tel:' + props.phone" type="button" class="w-full md:w-1/3 inline-flex items-center justify-center text-white mr-2 bg-sky-400 hover:bg-sky-500                     
                    font-medium rounded-lg py-3.5 me-2 mb-3
                  ">
                    <svg class="mr-2 w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                    позвонить по телефону
                </a>

                <Modal :ModalBtnText="'записаться на консультацию'" class="w-full md:w-1/3" />
            </div>


            <div v-if="props.maps" class="flex items-center justify-center mt-5">
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
    </div>
</template>
