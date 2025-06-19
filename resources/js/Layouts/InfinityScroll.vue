<script setup>
import Bundle from "@/Components/Bundle.vue";

defineProps({
  bundles: Object,
  auth: Object,
});
</script>

<template>
  <div class="text-center mb-10">
    <h1
      class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
    >
      Наша практика, новости и вопросы
    </h1>
  </div>
  <div class="flex justify-center px-3 md:px-10">
    <div
      class="w-full grid grid-cols-1 lg:grid-cols-2 gap-4 place-content-center"
    >
      <Bundle :bundles="bundles" :auth="auth" />
    </div>
  </div>

  <div class="text-center">
    <h1
      class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight mt-20 mb-10"
    >
      Наши сервисы
    </h1>

    <div class="flex flex-row items-center" id="utp">
      <div class="flex justify-center px-3 md:px-10 basis-1/2">
        <div class="w-full p-6 bg-white grid grid-cols-1 content-between">
          <a
            href="pension/create"
            class="hover:text-gray-900 hover:underline text-2xl/7 mb-2"
            >Прозрачный юрист</a
          >
          Выработали максимально понятную систему контроля качества
        </div>
      </div>
      <div class="basis-1/2">
        <p class="mb-5">
          Работаем только по договору,
          <br /> юрист отчитывается на каждом этапе, 
          <br />прогнозируем ход судебного процесса,
          <br />оплата по факту работы
        </p>

      </div>
    </div>

    <div class="flex flex-row items-center">
      <div class="flex justify-center px-3 md:px-10 basis-1/2">
        <div class="w-full p-6 bg-white grid grid-cols-1 content-between">
          <a
            href="pension/create"
            class="hover:text-gray-900 hover:underline text-2xl/7 mb-2"
            >Калькулятор пенсии по старости</a
          >
          Считаем ИПК как профи
        </div>
      </div>
      <div class="basis-1/2">
        <p class="mb-5">
          Никогда расчет пенсии не был таким прозрачным <br />Мы можем проверить
          Вашу пенсию, расчитать ИПК перед выходом
        </p>
        <a
          href="/pension/create"
          class="text-white cursor-pointer hover:bg-blue-700 right-2.5 bottom-2.5 bg-blue-600 font-medium rounded-lg text-sm px-4 py-2"
        >
          перейти
        </a>
      </div>
    </div>

    <div class="flex flex-row items-center">
      <div class="flex justify-center px-3 md:px-10 basis-1/2">
        <div class="w-full p-6 bg-white grid grid-cols-1 content-between">
          <a
            href="pension/create"
            class="hover:text-gray-900 hover:underline text-2xl/7 mb-2"
            >Вопрос юристу онлайн</a
          >
          Отвечаем в считанные секунды
        </div>
      </div>
      <div class="basis-1/2">
        <p class="my-5">
          Даем советы онлайн, но осторожно,<br />мы за живое общение
        </p>
        <a
          href="/questions/add"
          class="text-white cursor-pointer hover:bg-blue-700 right-2.5 bottom-2.5 bg-blue-600 font-medium rounded-lg text-sm px-4 py-2"
        >
          задать вопрос
        </a>
      </div>
    </div>
  </div>
</template>