<template>
  <footer
    class="p-4 bg-white sticky top-[100vh] flex flex-col items-center mb-2 dark:bg-gray-800"
  >
    <slot />
    <span
      class="text-sm md:w-2/3 mb-1 pb-1 text-gray-500 md:text-center border-b"
      >© 2025
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
