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
        <div class="grid md:grid-cols-3 md:gap-4">
          <!--modal btn -->
          <div class="hidden md:flex items-center justify-start px-2">
            <Modal :ModalBtnText="ModalBtnText" />
          </div>
          <!--modal btn -->

          <!--tg btn -->
          <TgBtn class="hidden md:flex" />
          <!--tg btn -->

          <div v-if="props.phone" class="grid gap-0 md:gap-3 grid-cols-2 md:grid-cols-1 place-items-center">
            <div class="flex-auto">
              <a :href="phoneto" class="text-xl">{{ phone }} </a>
            </div>
            <!--tg btn -->
            <TgBtn class="flex md:hidden" />
            <!--tg btn -->
          </div>

          <div
            v-else
            class="grid gap-0 md:gap-3 grid-cols-3 md:grid-cols-2 place-items-center"
          >
            <a :href="phoneto" class="text-xl">{{ phone }} </a>

            <!--tg btn -->
            <TgBtn class="flex md:hidden" />
            <!--tg btn -->

            <a v-if="secondphone" :href="secondphoneto" class="text-xl">{{
              secondphone
            }}</a>
          </div>
        </div>
      </h2>
    </div>
  </header>
</template>
