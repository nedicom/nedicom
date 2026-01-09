<script setup>
import ModalPhone from "@/Components/ModalPhone.vue";

let address = "Респ. Крым, г. Симферополь, ул. Долгоруковская, 5";
let phone = "+7 978 8838 978";

const props = defineProps({
  usl_name: String,
  address: String,
  dopadress: String,
  phone: String,
  maps: String,
  metaimage: String,
  company: String,
  region: Object,
  backendurl: String,
  tracking: {
    type: Object,
    default: () => ({})
  }
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
  <div class="md:flex justify-center p-2 border-b-4 border-indigo-500" id="contacts">
    <div class="w-full">
      <section>
        <div class="px-4 grid justify-items-center py-12">
          <h3 class="m-0 font-semibold text-3xl tracking-tight  block text-center justify-items-center items-center">
            Контакты
          </h3>
          <div class="text-center lg:text-2xl lg:py-6 pt-3">
            Позвоните сейчас или сохраните номер юриста
          </div>
          <div class="flex w-full justify-center lg:justify-start my-6 lg:my-0">
            <div class="w-4/5 lg:w-full font-bold grid gap-y-4">
              <div class="w-full flex items-center animate-custom-pulse">
                <ModalPhone :key="backendurl" :tracking="$page.props.tracking" :backendurl="props.backendurl"
                  :phone="phone" :lawyer="'Позвонить'" :phoneto="'tel:' + phone" :avatarPath="null" />
              </div>

              <div v-if="props.maps" class="w-full flex justify-center items-center mb-5">
                <a :href="props.maps" onclick="ym(24900584, 'reachGoal', 'YaMap_Click'); return true;"
                  class="w-full hover:bg-slate-100 h-12 md:w-48 xl:w-64 flex flex-col text-center items-center justify-center py-2 text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-300 font-medium px-3"
                  target="_blank">
                  <div class="flex items-center">
                    <span class="h-full mr-1 flex items-center"><img class="h-4"
                        src="https://yandex.ru/maps/favicon.svg" /></span>

                    <div class="flex">
                      <span class="font-bold h-full mr-1 lg:text-xs xl:text-base">
                        <span class="text-red-600">Я</span>ндекс карты
                      </span>
                    </div>
                    <div class="flex items-center">
                      <span v-for="x in 5" :key="x">
                        <svg class="w-3 h-3 text-yellow-300 block" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                          fill="currentColor" viewBox="0 0 22 20">
                          <path
                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                        </svg>
                      </span>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>

          <div class="flex items-center">
            <div class="text-center lg:text-left h-full grid grid-cols-1 place-content-around gap-4 lg:p-5">
              <!-- Исправленная микроразметка -->
              <div itemscope itemtype="https://schema.org/Service">
                <div itemprop="provider" itemscope itemtype="https://schema.org/Person">
                  <meta itemprop="name" :content="props.company">
                  <meta itemprop="telephone" :content="phone">

                  <div itemprop="workLocation" itemscope itemtype="https://schema.org/Place">
                    <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                      <p v-if="dopadress" class="font-semibold text-2xl tracking-tight">
                        Главный офис:
                      </p>
                      <p itemprop="streetAddress" class="font-semibold text-xl lg:text-2xl">
                        {{ address }}
                      </p>
                      <meta itemprop="addressCountry" content="RU" />
                      <meta itemprop="addressLocality" :content="region.region" />
                    </div>
                  </div>
                </div>

                <p v-if="dopadress" class="font-semibold mt-6 text-2xl tracking-tight">
                  Дополнительные офисы
                </p>
                <p v-if="dopadress" v-html="dopadress" class="text-base mt-6 font-normal"></p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>