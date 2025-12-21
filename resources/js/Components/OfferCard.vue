<script setup>
import { ref } from "vue";

import Modal from "@/Components/Modal.vue";
import RatingReady from "@/Components/RatingReady.vue";
import ShareButtons from "@/Components/ShareButtons.vue";

let set = defineProps({
  offer: Object,
  city: Object,
  getlwr: String,
  auth: Object,
});

let ModalBtnText = "На консультацию";
let secondtext = "";
</script>

<template>
  <section class="my-5">
    <div
      class="flex flex-col md:flex-row mx-auto items-center"
      itemprop="offers"
      itemscope
      itemtype="https://schema.org/Offer"
    >
      <div class="w-full md:w-1/3 h-72 flex justify-center">
        <a
          v-if="set.offer.type == 'uslugi'"
          :href="
            route('uslugi.canonical.url', [
              set.offer.cities.url,
              set.offer.main.url,
              set.offer.second.url,
              set.offer.clean_url,
            ])
          "
          class="relative w-full rounded-lg flex justify-center"
        >
          <img
            class="h-full rounded-lg shadow-xl object-cover"
            :src="'https://nedicom.ru/' + set.offer.file_path"
            :alt="set.offer.usl_name"
            loading="lazy"
            itemprop="image"
            onerror="this.style.display='none'"
          />
        </a>

        <a
          v-if="set.offer.type == 'user'"
          :href="route('lawyer', [set.offer.url])"
          class="relative w-full rounded-lg shadow-xl flex justify-center"
        >
          <img
            class="h-full rounded-lg shadow-xl object-cover transition-opacity group-hover:opacity-90"
            :src="'https://nedicom.ru/' + set.offer.file_path"
            :alt="set.offer.usl_name"
            loading="lazy"
            itemprop="image"
            onerror="this.style.display='none'"
          />

          <div
            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 transition-all group-hover:bg-opacity-50 rounded-lg shadow-xl"
          >
            <span class="text-white text-xl font-bold tracking-wide">{{
              set.offer.usl_name
            }}</span>
          </div>
        </a>
      </div>

      <div class="w-full md:w-2/3 px-5 md:px-10">
        <div class="mb-4 flex items-center justify-between gap-4">
          <span v-if="set.offer.type == 'uslugi'">
            <ShareButtons :bundle="set.offer" :auth="set.auth" />
          </span>
          <span v-else></span>

          <div class="flex items-center justify-end gap-1">
            <button
              type="button"
              data-tooltip-target="tooltip-quick-look"
              class="flex items-center gap-1 rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
            >
              <span class="sr-only">перейти</span>
              <span class="text-xs">{{ set.offer.counter }}</span>
              <svg
                class="h-5 w-5"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  stroke="currentColor"
                  stroke-width="2"
                  d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"
                />
                <path
                  stroke="currentColor"
                  stroke-width="2"
                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                />
              </svg>
            </button>
            <div
              id="tooltip-quick-look"
              role="tooltip"
              class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700"
              data-popper-placement="bottom"
            >
              Скоро будет подробная статистика
              <div class="tooltip-arrow" data-popper-arrow=""></div>
            </div>
          </div>
        </div>

        <a
          v-if="set.offer.type == 'uslugi'"
          itemprop="url"
          :href="
            route('uslugi.canonical.url', [
              set.offer.cities.url,
              set.offer.main.url,
              set.offer.second.url,
              set.offer.clean_url,
            ])
          "
          class="text-lg font-semibold leading-tight text-gray-900 hover:underline cursor-pointer"
          ><span itemprop="name">{{ set.offer.usl_name }}</span></a
        >

        <a
          v-if="set.offer.type == 'user'"
          itemprop="url"
          :href="route('lawyer', [set.offer.url])"
          class="text-lg font-semibold leading-tight text-gray-900 hover:underline cursor-pointer"
          ><span itemprop="name">{{ set.offer.usl_name }}</span></a
        >

        <div class="mt-2 flex items-center gap-2">
          <div class="flex items-center">
            <RatingReady
              :rating="set.offer.final_rating"
              :reviewRating="false"
            />
            <span
              class="flex items-center justify-center ml-1 text-sm font-bold text-gray-900"
            >
              <span v-if="!isNaN(set.offer.total_count)"
                >отзывов - {{ set.offer.total_count }}</span
              >
              <span v-else>отзывов - 0</span>
            </span>
          </div>
        </div>

        <ul
          v-if="set.offer.type == 'uslugi'"
          class="mt-2 flex flex-col items-start gap-1 px-0"
        >
          <li class="flex items-center gap-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4 text-gray-500 dark:text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"
              />
            </svg>

            <p
              v-if="set.offer.cities"
              class="text-sm font-medium text-gray-500 dark:text-gray-400"
            >
              {{ set.offer.cities.title }}
            </p>
          </li>

          <li v-if="set.offer.main" class="flex items-center gap-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="h-4 w-4 text-gray-500 dark:text-gray-400"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"
              />
            </svg>

            <p
              v-if="set.offer.type == 'uslugi'"
              class="text-sm font-medium text-gray-500 dark:text-gray-400"
            >
              {{ set.offer.main.name }}
            </p>
          </li>

          <li v-if="set.offer.second" class="flex items-center gap-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="h-4 w-4 text-gray-500 dark:text-gray-400"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"
              />
            </svg>

            <p
              v-if="set.offer.type == 'uslugi'"
              class="text-sm font-medium text-gray-500 dark:text-gray-400"
            >
              {{ set.offer.second.name }}
            </p>
          </li>
        </ul>
        <span
          class="text-sm font-bold text-gray-700 line-clamp-3"
          itemprop="description"
          >{{ set.offer.usl_desc }}</span
        >

        <div class="mt-4 flex items-center justify-between gap-4">
          <p
            v-if="set.offer.type == 'uslugi'"
            class="text-2xl font-extrabold leading-tight text-gray-900"
          >
            {{ set.offer.price }} р.
          </p>
          <p v-else class="text-2xl font-extrabold leading-tight text-gray-900">
            1000 р.
          </p>
          <meta itemprop="price" :content="set.offer.price" />
          <meta itemprop="priceCurrency" content="RUB" />
          <meta itemprop="priceValidUntil" content="01-01-2026" />
          <link itemprop="availability" href="http://schema.org/InStock" />

          <!-- shippingDetails -->
          <div
            itemprop="shippingDetails"
            itemtype="https://schema.org/OfferShippingDetails"
            itemscope
          >
            <div
              itemprop="shippingRate"
              itemtype="https://schema.org/MonetaryAmount"
              itemscope
            >
              <meta itemprop="value" content="0" />
              <meta itemprop="currency" content="RUB" />
            </div>
            <div
              itemprop="shippingDestination"
              itemtype="https://schema.org/DefinedRegion"
              itemscope
            >
              <meta itemprop="addressCountry" content="RU" />
            </div>
            <div
              itemprop="deliveryTime"
              itemtype="https://schema.org/ShippingDeliveryTime"
              itemscope
            >
              <div
                itemprop="handlingTime"
                itemtype="https://schema.org/QuantitativeValue"
                itemscope
              >
                <meta itemprop="minValue" content="0" />
                <meta itemprop="maxValue" content="1" />
                <meta itemprop="unitCode" content="DAY" />
              </div>
              <div
                itemprop="transitTime"
                itemtype="https://schema.org/QuantitativeValue"
                itemscope
              >
                <meta itemprop="minValue" content="1" />
                <meta itemprop="maxValue" content="5" />
                <meta itemprop="unitCode" content="DAY" />
              </div>
            </div>
          </div>

          <!-- Return Policy -->
          <div
            itemprop="hasMerchantReturnPolicy"
            itemscope
            itemtype="https://schema.org/MerchantReturnPolicy"
          >
            <meta itemprop="applicableCountry" content="RU" />
            <meta itemprop="returnPolicyCountry" content="RU" />
            <meta
              itemprop="returnPolicyCategory"
              content="https://schema.org/MerchantReturnFiniteReturnWindow"
            />
            <meta itemprop="merchantReturnDays" content="30" />
            <meta
              itemprop="returnMethod"
              content="https://schema.org/ReturnInStore"
            />
            <meta
              itemprop="returnFees"
              content="https://schema.org/FreeReturn"
            />
          </div>

          <!--
          <div class="hidden md:flex items-center justify-start px-2">
            <Modal :ModalBtnText="ModalBtnText" :secondtext="secondtext" :modalPageTitle="'страница поиска услуг - ' +
              set.offer.usl_name
              " />
          </div>
          -->
        </div>
      </div>
    </div>
  </section>
  <hr class="h-px my-8 bg-gray-200 md:my-10 border-0 dark:bg-gray-700" />
</template>