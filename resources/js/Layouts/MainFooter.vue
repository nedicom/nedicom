<template>
  <footer
    class="p-4 bg-white sticky top-[100vh] flex flex-col items-center mb-2 dark:bg-gray-800"
  >
    <slot />

    <!-- Наши инструменты -->
    <div class="w-full max-w-4xl border-t pt-6 mb-6">
      <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest text-center mb-4">Наши инструменты</p>
      <div class="flex flex-wrap justify-center gap-x-8 gap-y-3">
        <a href="/clientdashboard" class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-700 transition-colors">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Прозрачный юрист
        </a>
        <a href="/pension/create" class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-700 transition-colors">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
          Калькулятор пенсии
        </a>
        <a href="/calculator-ddu" class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-700 transition-colors">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          Калькулятор ДДУ
        </a>
      </div>
    </div>

    <span
      class="text-sm md:w-2/3 mb-1 pb-1 text-gray-500 md:text-center border-b"
      >© 2026
      <a href="https://nedicom.ru/" class="hover:underline">nedicom</a>™. Права
      на товарный знак зарегистрированы в Роспатенте
    </span>
    <ul
      class="flex flex-wrap list-none items-center mt-3 text-sm text-gray-500 sm:mt-0"
    >
      <li>
        <a href="/policy" class="hover:underline mx-1" rel="nofollow noopener noreferrer"
          >Политика в отношении персональных данных</a
        >
      </li>
      <li>
        <a href="/cookie" class="hover:underline mx-1" rel="nofollow noopener noreferrer"
          >Правила обработки cookie</a
        >
      </li>
      <li>
        <a href="/oferta" class="hover:underline mx-1" rel="nofollow noopener noreferrer"
          >Оферта</a
        >
      </li>
      <li>
        <a href="/personal" class="hover:underline mx-1" rel="nofollow noopener noreferrer"
          >Согласие на обработку персональных данных</a
        >
      </li>
      <li>
        <a href="/contacts" class="hover:underline mx-1">Контакты</a>
      </li>
    </ul>
  </footer>
</template>
