<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/inertia-vue3";

defineProps({
  auth: Object,
});
</script>

<template>
  <Head>
    <title>О компании</title>
    <meta
      name="description"
      content="Компания Мина - юристы всех категорий по Республике Крым и городу Москва."
    />
  </Head>

  <div class="min-h-screen">
    <MainHeader :auth="auth" />

    <Header :modalPageTitle="'О компании'" />

    <h1
      class="pt-24 pb-12 w-full text-center max-w-7xl px-4 md:px-5 lg:px-5 mx-auto text-gray-900 text-4xl font-bold font-manrope leading-normal"
    >
      Контакты
    </h1>

    <section class="pb-24 relative">
      <div class="w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto">
        <div
          itemscope
          itemtype="https://schema.org/Organization"
          class="w-full place-items-center gap-8 grid lg:grid-cols-2 grid-cols-1"
        >
          <div
            class="w-full flex-col justify-start lg:items-start items-center gap-10 inline-flex"
          >
            <div
              class="w-full flex-col justify-start lg:items-start items-center gap-4 flex"
            >
              <div
                class="text-gray-500 text-base font-normal leading-relaxed lg:text-start text-center"
              >
                <h2
                  itemprop="name"
                  class="text-gray-900 text-2xl font-bold font-manrope leading-normal lg:text-start text-center"
                >
                  Юридическая компания "Мина и партнеры"
                </h2>
                <p
                  class="text-gray-500 text-base font-normal leading-relaxed lg:text-start text-center mb-2"
                >
                  Квалифицированные юридические услуги. Ответсвенность,
                  качество, прогнозирование, отчетность, прозрачность.
                </p>
                <p
                  itemprop="address"
                  itemscope
                  itemtype="https://schema.org/PostalAddress"
                >
                  Адрес (Крым):
                  <span itemprop="streetAddress"
                    >Симферополь, ул. Долгоруковская 5
                  </span>
                  <span itemprop="postalCode">295000 </span>
                  <span itemprop="addressLocality">Респ. Крым, Россия</span>
                </p>

                телефон:
                <p itemprop="telephone">8 (978) 8838 978</p>
                e-mail:
                <p itemprop="email">m6132@yandex.ru</p>
                ГРН: 317910200050560
                <meta itemprop="iso6523Code" content="317910200050560" />
                ИНН: 910305709385
                <meta itemprop="iso6523Code" content="910305709385" />
                <p>
                  С уважением,
                  <span
                    itemprop="alumni"
                    itemscope
                    itemtype="https://schema.org/Person"
                  >
                    <span itemprop="name">Ольга Мина</span> </span
                  >,
                  <span
                    itemprop="alumni"
                    itemscope
                    itemtype="https://schema.org/Person"
                  >
                    <span itemprop="name">Москаленко Дмитрий</span>,
                  </span>
                  <span
                    itemprop="alumni"
                    itemscope
                    itemtype="https://schema.org/Person"
                  >
                    <span itemprop="name">Добренький Андрей</span>,
                  </span>
                  <span
                    itemprop="alumni"
                    itemscope
                    itemtype="https://schema.org/Person"
                  >
                    <span itemprop="name">Марк Мина</span>
                  </span>
                </p>
              </div>
            </div>

            <div class="flex justify-center mt-5 w-full">
              <div class="lg:w-1/2 w-2/3">
                <a
                  href="tel:89788838978"
                  type="button"
                  class="my-5 w-full inline-flex items-center justify-center text-white mr-2 bg-blue-600 hover:bg-blue-700 font-medium rounded-lg py-2.5"
                >
                  <svg
                    class="mr-2 w-6 h-6 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"
                    />
                  </svg>
                  <span class="flex flex-col">
                    <span class=""> 8 (978) 8838 978 </span>
                    <span class="text-xs"> обратиться по телефону </span>
                  </span>
                </a>
              </div>
            </div>
          </div>
          <div class="text-center">
            <h2
              class="text-gray-900 text-2xl font-bold font-manrope leading-normal text-center"
            >
              Платежные реквизиты
            </h2>
            <img
              itemprop="image"
              class="lg:mx-0 mx-auto rounded-3xl object-cover"
              src="storage/default/qr.jpg"
              alt="код для оплаты"
            />
            <a href="storage/default/obrazec.docx" download>
              <img
                itemprop="image"
                class="inline-block mx-auto object-cover h-24"
                src="storage/default/doc.png"
                alt="образец соглашения"
              />
            </a>

            <h2
              class="text-gray-900 text-2xl font-bold font-manrope leading-normal text-center mb-6"
            >
              образец соглашения
            </h2>

            <span v-if="auth">
              <a
                v-if="auth.isadmin == 1"
                href="storage/default/schet.docx"
                download
              >
                <img
                  itemprop="image"
                  class="inline-block mx-auto object-cover h-24"
                  src="storage/default/doc.png"
                  alt="наследственный юрист Москва"
                />
              </a>
            </span>
            <span v-else>
              <a href="storage/default/obrazecschet.docx" download>
                <img
                  itemprop="image"
                  class="inline-block mx-auto object-cover h-24"
                  src="storage/default/doc.png"
                  alt="наследственный юрист Москва"
                />
              </a>
            </span>
            <h2
              class="text-gray-900 text-2xl font-bold font-manrope leading-normal text-center mb-6"
            >
              счет
            </h2>

            <a href="storage/default/akt.docx" download>
              <img
                itemprop="image"
                class="inline-block mx-auto object-cover h-24"
                src="storage/default/doc.png"
                alt="наследственный юрист Москва"
              />
            </a>
            <h2
              class="text-gray-900 text-2xl font-bold font-manrope leading-normal text-center mb-6"
            >
              акт
            </h2>
          </div>
        </div>
      </div>
    </section>

    <MainFooter />
  </div>
</template>