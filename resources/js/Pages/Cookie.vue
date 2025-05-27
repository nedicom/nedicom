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
    <title>Правила (политика) обработки cookie</title>
    <meta name="description" content="Правовая информация" />
  </Head>

  <div class="h-screen">
    <MainHeader :auth="auth" />

    <Header ttl="Политика использования персональных данных" />

    <div class="container m-12">
      <h1 class="text-xl my-5">Правила (политика) обработки cookie</h1>
      <p><strong>Общие положения </strong></p>
      <p>
        Пользуясь сайтом, вы принимаете политику куки. Файлы куки (cookie) — это
        сведения, передаваемые веб-сервером на ваш компьютер, мобильный
        телефон или другое устройство, подключенное к Интернету, когда вы
        посещаете веб-сайт.
      </p>
      *Дополнительная информация: <br>
        <a href="/personal" class="text-blue-500 underline">согласие на обработку персональных данных</a>.<br>
        <a href="/policy" class="text-blue-500 underline">политика в отношении обработки персональных данных</a>.
      <p><strong>Зачем собирать</strong></p>
      <p>
        На нашем сайте мы собираем сведения для повышения удобства пользователя
        (например, сведения о городе посетителя сайта) и сведения необходимые
        для работы сервисов аналитики, таких как Яндекс Метрика. Подробнее -
        https://yandex.ru/legal/metrica_termsofuse/ru
      </p>
      <p><strong>Какие типы файлов куки мы используем</strong></p>

      <p>
        Файлы сессии (сеансовые куки) — сохраняются в браузере до тех пор, пока
        вы не покинете веб-сайт. Постоянные файлы — сохраняются в вашем браузере
        после закрытия сайта. Основные куки — файлы куки, которые связаны с
        основным доменом и сторонние куки — файлы куки из другого домена.
      </p>

      <p><strong>Как удалить</strong></p>
      <p>
        Удалить сведения cookie можно в настройках Вашего браузера. Там же можно
        отключить сбор указанных данных.
      </p>
    </div>

    <MainFooter />
  </div>
</template>
