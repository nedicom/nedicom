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
    <title>правила обработки cookie</title>
    <meta name="description" content="Правовая информация" />
  </Head>

  <div class="h-screen">
    <MainHeader :auth="auth" />

    <Header ttl="Политика использования персональных данных" />

    <div class="container m-12">
      <h1 class="text-xl my-5">правила обработки cookie</h1>
      <p><strong>Общие положения </strong></p>
      <p>
        Пользуясь сайтом, вы принимаете политику куки. Файлы куки (cookie) — это
        небольшие файлы, передаваемые веб-сервером на ваш компьютер, мобильный
        телефон или другое устройство, подключенное к Интернету, когда вы
        посещаете веб-сайт.
      </p>
      <p><strong>Зачем собирать</strong></p>
      <p>
        На нашем сайте мы вынуждены собирать файлы cookie поскольку этого требуют поисковые анализаторы yandex и google.
      </p>
      <p><strong>Как удалить</strong></p>
      <p>
        Достаточно удалить сведения cookie в Вашем браузере. Там же можно отключить сбор указанных данных.
      </p>
    </div>

    <MainFooter />
  </div>
</template>
