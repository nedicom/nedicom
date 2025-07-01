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
    <title>Оферта на юридические консультации | ИП Мина</title>
    <meta
      name="description"
      content="Оферта на оказание юридических консультаций"
    />
  </Head>

  <div class="min-h-screen">
    <MainHeader :auth="auth" />

          <div class="bg-white p-8 mx-auto my-10">
            <!-- Заголовок с УТП -->
            <div class="text-center">
              <h1 class="text-3xl font-bold text-gray-900 mb-3">
                Профессиональная
                <span class="text-blue-600">
                   платная консультация</span
                >
                юриста
              </h1>
              <h1 class="text-3xl font-bold text-gray-900 mb-3">
                Цена
                <span class="text-blue-600"> 2000</span> рублей
              </h1>
              <p class="text-lg text-gray-600">
                Быстрое решение сложных вопросов с гарантией конфиденциальности
              </p>
            </div>

            <div class="w-full flex justify-center my-12">
            <a
              href="https://t.me/advokatmina"
              class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-md flex items-center w-64 text-center"
            >
              Напишите в телеграм чтобы заказать консультацию
            </a>
            </div>

            <!-- Блок преимуществ -->
            <div class="grid md:grid-cols-3 gap-4 mb-8">
              <div class="bg-blue-50 p-4 rounded-lg text-center">
                <svg
                  class="w-10 h-10 mx-auto mb-2 text-blue-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                  ></path>
                </svg>
                <h3 class="font-semibold text-gray-800">Гарантия результата</h3>
                <p class="text-sm text-gray-600">Чёткий план действий</p>
              </div>
              <div class="bg-blue-50 p-4 rounded-lg text-center">
                <svg
                  class="w-10 h-10 mx-auto mb-2 text-blue-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
                <h3 class="font-semibold text-gray-800">Экономия времени</h3>
                <p class="text-sm text-gray-600">Ответ за 15 минут</p>
              </div>
              <div class="bg-blue-50 p-4 rounded-lg text-center">
                <svg
                  class="w-10 h-10 mx-auto mb-2 text-blue-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                  ></path>
                </svg>
                <h3 class="font-semibold text-gray-800">Опыт 10+ лет</h3>
                <p class="text-sm text-gray-600">
                  Специализация по вашему вопросу
                </p>
              </div>
            </div>
          </div>

    <h1
      class="pt-24 pb-12 w-full text-center max-w-7xl px-4 md:px-5 lg:px-5 mx-auto text-gray-900 text-4xl font-bold font-manrope leading-normal"
    >
      Оферта на оказание юридических консультаций
    </h1>

    <section class="pb-24 px-24">
      <div class="w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto">
        <div class="header-info">
          <p>
            <strong>ИП Мина</strong><br />
            ИНН: 910305709385<br />
            Тел.: 89788838978<br />
            Email: m6132@yandex.ru
          </p>
        </div>

        <h2>1. Предмет оферты</h2>
        <p>
          ИП Мина (далее — <span class="highlight">"Исполнитель"</span>)
          предлагает физическим и юридическим лицам (далее —
          <span class="highlight">"Клиент"</span>) заключить договор на оказание
          юридических консультаций на условиях настоящей оферты.
        </p>

        <h2>2. Условия оказания услуг</h2>
        <ul>
          <li><strong>Стоимость консультации:</strong> 2 000 рублей.</li>
          <li>
            <strong>Формат консультации:</strong> устная (онлайн/офлайн) или
            письменная (ответ на вопрос в текстовом виде).
          </li>
          <li>
            <strong>Продолжительность консультации:</strong> до 60 минут (для
            устной консультации).
          </li>
          <li>
            <strong>Тематика консультаций:</strong> гражданское, трудовое,
            семейное, налоговое право, защита прав потребителей и другие
            юридические вопросы.
          </li>
        </ul>

        <h2>3. Порядок оказания услуг</h2>
        <ol>
          <li>
            Клиент оставляет заявку на консультацию через сайт, по телефону,
            email, иным способом.
          </li>
          <li>Исполнитель согласовывает дату и время консультации.</li>
          <li>
            Клиент оплачивает услугу любым удобным способом (qr код на странице
            настйщего сайта "Реквизиты").
          </li>
          <li>После оплаты проводится консультация в выбранном формате.</li>
        </ol>

        <h2>4. Оплата</h2>
        <ul>
          <li>Оплата производится в полном объеме до начала консультации.</li>
        </ul>

        <h2>5. Конфиденциальность</h2>
        <p>
          Исполнитель обязуется не разглашать информацию, полученную от Клиента,
          за исключением случаев, предусмотренных законодательством РФ.
        </p>

        <h2>6. Заключительные положения</h2>
        <ul>
          <li>
            Совершение оплаты означает полное согласие Клиента с условиями
            оферты.
          </li>
          <li>
            Исполнитель оставляет за собой право изменить условия оферты,
            уведомив Клиента через сайт или иным способом.
          </li>
        </ul>

        <h2>Реквизиты для оплаты:</h2>
        <p>
          <a
            href="https://nedicom.ru/contacts"
            class="text-blue-600 hover:underline"
            >Оплата производится сканированием qr</a
          >
        </p>

        <div class="signature">
          <p><strong>ИП Мина О. В.</strong><br /></p>
        </div>
      </div>
    </section>

    <MainFooter />
  </div>
</template>