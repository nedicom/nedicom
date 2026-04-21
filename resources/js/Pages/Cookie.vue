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
    <title>Правила обработки cookie</title>
    <meta name="description" content="Правила обработки cookie файлов в соответствии с требованиями законодательства" />
  </Head>

  <div class="min-h-screen bg-gray-50">
    <MainHeader :auth="auth" />

    <div class="container mx-auto px-4 py-8 max-w-4xl">
      <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-6 text-gray-900 border-b pb-4">
          Правила (политика) обработки cookie
        </h1>

        <div class="prose prose-gray max-w-none">
          <div class="bg-blue-50 p-4 rounded-lg mb-6">
            <p class="text-sm text-gray-700">
              <strong>📌 Дополнительная информация:</strong><br>
              <a href="/personal" class="text-blue-600 underline">Согласие на обработку персональных данных</a><br>
              <a href="/policy" class="text-blue-600 underline">Политика в отношении обработки персональных данных</a>
            </p>
          </div>

          <h2>1. Общие положения</h2>
          <p>
            Файлы cookie — это сведения, передаваемые веб-сервером на ваш компьютер, мобильный
            телефон или другое устройство, подключенное к Интернету, когда вы посещаете веб-сайт.
            Пользуясь сайтом, вы даете согласие на обработку cookie в порядке, определенном настоящими Правилами.
          </p>
          <p>
            Настоящие Правила разработаны в соответствии с требованиями Федерального закона 
            от 27.07.2006 № 152-ФЗ «О персональных данных» и рекомендациями Роскомнадзора.
          </p>

          <h2>2. Цели сбора cookie</h2>
          <p>
            На нашем сайте мы собираем файлы cookie для следующих целей:
          </p>
          <ul>
            <li>повышение удобства пользователя (например, сохранение выбранного города, настроек отображения);</li>
            <li>запоминание настроек авторизации (вход в личный кабинет);</li>
            <li>работа сервисов аналитики (Яндекс.Метрика);</li>
            <li>сбор статистики посещаемости и поведения пользователей на сайте;</li>
            <li>защита от спама и злоупотреблений.</li>
          </ul>
          <p>
            Подробнее об обработке данных с помощью Яндекс.Метрики: 
            <a href="https://yandex.ru/legal/metrica_termsofuse/ru" target="_blank" class="text-blue-600 underline">
              https://yandex.ru/legal/metrica_termsofuse/ru
            </a>
          </p>

          <h2>3. Типы файлов cookie</h2>
          
          <h3>3.1. По сроку хранения</h3>
          <ul>
            <li><strong>Сессионные (сеансовые) cookie</strong> — сохраняются в браузере до тех пор, пока вы не покинете веб-сайт. Необходимы для работы форм, корзины, авторизации.</li>
            <li><strong>Постоянные cookie</strong> — сохраняются в вашем браузере после закрытия сайта. Используются для запоминания настроек пользователя.</li>
          </ul>

          <h3>3.2. По принадлежности</h3>
          <ul>
            <li><strong>Основные (first-party) cookie</strong> — файлы cookie, которые связаны с основным доменом nedicom.ru.</li>
            <li><strong>Сторонние (third-party) cookie</strong> — файлы cookie от сервисов аналитики (Яндекс.Метрика), используемые для сбора статистики.</li>
          </ul>

          <h2>4. Управление cookie</h2>
          <p>
            Вы можете управлять файлами cookie в настройках вашего браузера:
          </p>
          <ul>
            <li>полностью отключить сохранение cookie;</li>
            <li>удалить уже сохраненные cookie;</li>
            <li>настроить уведомление при сохранении новых cookie.</li>
          </ul>
          <p>
            <strong>Важно:</strong> Отключение cookie может привести к некорректной работе некоторых функций сайта 
            (например, авторизации, сохранения настроек).
          </p>
          <p>
            Инструкции для популярных браузеров:
          </p>
          <ul>
            <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" class="text-blue-600 underline">Google Chrome</a></li>
            <li><a href="https://support.mozilla.org/ru/kb/udalenie-kuk-dlya-udaleniya-informacii-ot-web-sajtov" target="_blank" class="text-blue-600 underline">Mozilla Firefox</a></li>
            <li><a href="https://support.apple.com/ru-ru/guide/safari/sfri11471/mac" target="_blank" class="text-blue-600 underline">Safari</a></li>
            <li><a href="https://support.microsoft.com/ru-ru/windows" target="_blank" class="text-blue-600 underline">Microsoft Edge</a></li>
          </ul>

          <h2>5. Срок хранения cookie</h2>
          <p>
            Срок хранения cookie определяется их типом:
          </p>
          <ul>
            <li>сессионные cookie — до закрытия браузера;</li>
            <li>постоянные cookie — до истечения срока, установленного в настройках файла (обычно до 1 года), либо до их удаления пользователем.</li>
          </ul>
          <p>
            Данные, собранные через Яндекс.Метрику, обрабатываются в соответствии с 
            <a href="/personal" class="text-blue-600 underline">Согласием на обработку персональных данных</a> 
            и хранятся не дольше 1 года с даты последнего посещения сайта.
          </p>

          <h2>6. Передача данных третьим лицам</h2>
          <p>
            Информация, собранная с помощью cookie, не передается третьим лицам, за исключением:
          </p>
          <ul>
            <li>сервисов аналитики (Яндекс.Метрика) — в обезличенном виде;</li>
            <li>случаев, предусмотренных законодательством РФ.</li>
          </ul>

          <h2>7. Изменение Правил</h2>
          <p>
            Оператор оставляет за собой право вносить изменения в настоящие Правила. 
            Актуальная версия всегда доступна по адресу 
            <a href="/cookie" class="text-blue-600 underline">https://nedicom.ru/cookie</a>.
          </p>

          <hr class="my-6">
          <p class="text-sm text-gray-500">Дата последнего обновления: 21.04.2026</p>
        </div>
      </div>

      <MainFooter />
    </div>
  </div>
</template>

<style scoped>
.prose {
  font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
  line-height: 1.5;
  color: #374151;
}

.prose h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
  color: #1f2937;
  border-left: 3px solid #3b82f6;
  padding-left: 0.75rem;
}

.prose h3 {
  font-size: 1.125rem;
  font-weight: 600;
  margin-top: 1rem;
  margin-bottom: 0.5rem;
  color: #374151;
}

.prose p {
  margin-bottom: 0.75rem;
}

.prose ul {
  margin-bottom: 0.75rem;
  padding-left: 1.5rem;
}

.prose li {
  margin-bottom: 0.25rem;
}

.prose a {
  color: #2563eb;
  text-decoration: underline;
}

.prose a:hover {
  color: #1d4ed8;
}

@media print {
  .prose {
    color: #000;
  }
  .prose h2 {
    border-left-color: #000;
  }
}
</style>