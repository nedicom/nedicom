<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { Head } from "@inertiajs/inertia-vue3";

defineProps({
  auth: Object,
});
</script>

<template>
  <Head>
    <title>Согласие на обработку персональных данных</title>
    <meta name="description" content="Правовая информация" />
  </Head>

  <div class="h-screen">
    <MainHeader :auth="auth" />

    <Header ttl="Согласие на обработку персональных данных" />

    <div class="container m-12">
      <h1 class="text-xl my-5">Согласие на обработку персональных данных</h1>

      *Дополнительная информация: <br />
      <a href="/policy" class="text-blue-500 underline">политика в отношении обработки персональных данных</a>.<br>
      <a href="/cookie" class="text-blue-500 underline">правила обработки cookie</a>.<br>

      <div class="consent-text">
        <p>
          Вы соглашаетесь в соответствии с Федеральным законом от 27.07.2006 №
          152-ФЗ «О персональных данных» на обработку на сайте
          <strong>nedicom.ru</strong>
          на на условиях, изложенных в политике на обработку персональных
          данных, включающих (в случае указания этих сведений):
        </p>

        <ul>
          <li>ФИО ;</li>
          <li>контактные данные (телефон, e-mail);</li>
          <li>город;</li>
          <li>изображение (аватар)</li>
          <li>иные данные, необходимые для оказания услуг.</li>
        </ul>

        <p><strong>Цели обработки:</strong></p>
        <ul>
          <li>Оказание услуг;</li>
          <li>Запись на прием и обратная связь;</li>
          <li>Соблюдение требований законодательства.</li>
        </ul>

        <p>
          <strong>Способы обработки:</strong> автоматизированные и
          неавтоматизированные, включая сбор, запись, хранение, уточнение,
          передачу (в т.ч. третьим лицам, если это необходимо для оказания
          услуг).
        </p>

        <p>
          Срок действия согласия: <strong>до отзыва</strong>, который может быть
          осуществлен путем направления письменного уведомления по адресу
          оператора.
        </p>

        <p>
          Подтверждено, ознакомление с
          <strong>Политикой обработки персональных данных</strong> на сайте
          <strong>nedicom.ru</strong>.
        </p>
        <br>
                <p>
          Отозвать согласие можно направив заявление в свободной форме на адрес m6132@yandex.ru
        </p>
      </div>

      <MainFooter />
    </div>
  </div>
</template>
