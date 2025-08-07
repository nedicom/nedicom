<script setup>
import Chart from "@/Layouts/Chart.vue";

defineProps({
  userprices: Object,
  subheader: String,
  city: Object,
  secondbannerimgmobile: String,
  reviewcoutnt: Number,
  rating: Number,
  metaimage: String,
  keyword: String,
});
</script>

<template>
  <section
    id="prices"
    v-if="userprices[0]"
    class="bg-white py-8 antialiased md:py-16 border-b-4 border-indigo-500"
  >
    <!--   <meta itemprop="priceRange" content="1000р. - 2000р.">-->
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
      <div class="mx-auto max-w-5xl">
        <div class="gap-4 flex items-center">
          <h2 class="font-semibold mt-6 text-2xl tracking-tight text-center">
            Цены
          </h2>
        </div>

        <div class="mt-6 flow-root sm:mt-8">
          <div
            v-for="item in userprices"
            :key="item"
            itemprop="offers"
            class="py-3"
            itemscope
            itemtype="http://schema.org/Offer"
          >
            <div class="flex items-center">
              <div
                href="#"
                itemprop="name"
                class="flex w-full text-lg content-center font-semibold text-gray-900 hover:underline sm:col-span-10 lg:col-span-3"
              >
                {{ item.name }}:
              </div>

              <div v-if="item.price != 0" class="flex">
                <div
                  class="text-gray-500 ml-2"
                  itemprop="price"
                  :content="item.price"
                >
                  {{ item.price }}
                </div>
                <span itemprop="priceCurrency" class="inline ml-1" content="RUB"
                  >рублей</span
                >
              </div>

              <div v-else class="text-gray-500">
                бесплатно<meta itemprop="price" :content="item.price" />
              </div>

              <div
                class="flex items-center space-x-2 sm:col-span-4 lg:col-span-3"
              >
                <meta itemprop="priceValidUntil" content="2027-01-01" />
                <meta
                  itemprop="availability"
                  content="https://schema.org/InStock"
                />
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
                      <meta itemprop="minValue" content="0" />
                      <meta itemprop="maxValue" content="1" />
                      <meta itemprop="unitCode" content="DAY" />
                    </div>
                  </div>
                </div>

                <div
                  itemprop="hasMerchantReturnPolicy"
                  itemtype="https://schema.org/MerchantReturnPolicy"
                  itemscope
                >
                  <meta itemprop="applicableCountry" content="RU" />
                  <meta
                    itemprop="returnMethod"
                    content="https://schema.org/ReturnByMail"
                  />
                  <meta
                    itemprop="returnFees"
                    content="https://schema.org/FreeReturn"
                  />
                  <meta
                    itemprop="returnPolicyCategory"
                    content="https://schema.org/MerchantReturnFiniteReturnWindow"
                  />
                  <meta itemprop="merchantReturnDays" content="3" />
                </div>

                <!-- Component Start -->
                <div
                  v-if="item.price != 0"
                  class="relative flex flex-col items-center group"
                >
                  <svg
                    class="w-5 h-5"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                      clip-rule="evenodd"
                    />
                  </svg>

                  <div
                    class="max-w-sm absolute left-0 top-1/2 hidden flex-col items-center -translate-y-1/2 -translate-x-full mb-0 group-hover:flex"
                  >
                    <span
                      v-if="city"
                      class="relative rounded-md z-50 p-4 text-xs leading-none text-white bg-black shadow-lg"
                    >
                      По статистике за 2025 год, диапазон цены услуги
                      {{ subheader }} в регионе {{ city.title }} от
                      {{ item.price / 2 }} р. до {{ item.price * 2 }} р.
                    </span>
                  </div>
                </div>

                <!-- Component End  -->
              </div>
            </div>

            <div class="text-gray-500 text-sm py-2" itemprop="description">
              {{ item.value }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>