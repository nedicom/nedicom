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
        <div v-if="props.maps" class="md:w-1/2 w-full" style="height:400px">
            <iframe :src="props.maps" width="100%" height="400" frameborder="0">
            </iframe>
        </div>

        <div class="grid grid-cols-1 gap-4 place-content-evenly md:w-1/2 w-full px-3 text-md" style="height:400px">

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
