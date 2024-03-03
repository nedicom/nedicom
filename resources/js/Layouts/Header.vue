<script setup>
import Modal from "@/Components/Modal.vue";

let address = "Крым";
let secondaddress = "Москва";
let thirdaddress = "Краснодар";
let phone = "8 978 8838978";
let secondphone = "8 985 5582170";
let thirdphone = "8 978 1453925";

const props = defineProps({
  address: String,
  phone: String,
});

if (props.phone) {
  phone = props.phone;
}

let phoneto = "tel:" + phone;
let secondphoneto = "tel:" + secondphone;
let thirdphoneto = "tel:" + thirdphone;
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
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h2
        class="font-semibold text-gray-800 leading-tight grid gap-4 md:grid-cols-2 text-center"
      >
        <div class="grid gap-4 md:grid-cols-2">
          <div class="flex items-center justify-center px-2">
            <Modal :ModalBtnText="ModalBtnText" />
          </div>
        </div>

        <div v-if="props.phone" class="flex">
          <div class="flex-auto">
            <a :href="phoneto" class="text-3xl">{{ phone }} </a>
          </div>
        </div>

        <div v-else class="flex">
          <div class="text-xl">
            <a :href="phoneto">{{ address }}</a
            ><br />
            <a :href="phoneto" class="text-xl">{{ phone }} </a>
          </div>

          <div class="text-xl">
            <a :href="secondphoneto">{{ secondaddress }}</a>
            <br />
            <a
              v-if="secondphone"
              :href="secondphoneto"
              class="text-xl"
              >{{ secondphone }}</a
            >
          </div>

          <div class="text-xl">
            <a :href="thirdphoneto">{{ thirdaddress }}</a
            ><br />
            <a
              v-if="thirdphone"
              :href="thirdphoneto"
              class="text-xl"
              >{{ thirdphone }}</a
            >
          </div>
        </div>
      </h2>
    </div>
  </header>
</template>
