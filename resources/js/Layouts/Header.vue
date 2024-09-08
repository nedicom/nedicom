<script setup>
import Modal from "@/Components/Modal.vue";
import TgBtn from "@/Components/TgBtn.vue";

let address = "Крым";
let secondaddress = "Москва";
//let thirdaddress = "Краснодар";
let phone = "89788838978";
let secondphone = "89855582170";
//let thirdphone = "8 978 1453925";

const props = defineProps({
  address: String,
  phone: String,
});

if (props.phone) {
  phone = props.phone;
}

let phoneto = "tel:" + phone;
let secondphoneto = "tel:" + secondphone;
//let thirdphoneto = "tel:" + thirdphone;
let ModalBtnText = "записаться на консультацию";
</script>


<template>
  <!-- Page Heading -->
  <noscript>
    <div>
      <img
        src="https://mc.yandex.ru/watch/24900584"
        style="position: absolute; left: -9999px"
      />
    </div>
  </noscript>
  <header class="bg-white shadow sticky inset-x-0 -top-1 h-16 z-40">
    <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-4">
      <h2 class="font-semibold text-gray-800 leading-tight text-center">
        <div class="grid md:grid-cols-2 md:gap-4">
          <!--modal btn -->
          <div class="hidden md:flex items-center justify-start px-2">
            <Modal :ModalBtnText="ModalBtnText" />
          </div>
          <!--modal btn -->

          <div v-if="props.phone" class="grid grid-cols-1 content-center">
              <a :href="phoneto" class="text-2xl">{{ phone }} </a>
          </div>

          <div
            v-else
            class=""
          >
            <a :href="phoneto" class="text-2xl">{{ phone }} </a>

            <a v-if="secondphone" :href="secondphoneto" class="text-xl">{{
              secondphone
            }}</a>
          </div>
        </div>
      </h2>
    </div>
  </header>
</template>
