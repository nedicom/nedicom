<script setup>
let set = defineProps({
  phone: String,
});
</script>

<template>
  <a
    :href="
      'https://wa.me/' +
      set.phone.replace(/\D/g, '') +
      '?text=Здравствуйте. Когда к Вам можно обратиться за консультацией?'
    "
    onclick="ym(24900584, 'reachGoal', 'Wa_Click'); return true;"
    type="button"
    class="inline-flex items-center h-10 w-full md:w-48 xl:w-64 mx-1 md:mx-auto py-1 px-2 xl:py-2.5 xl:px-5 font-medium focus:outline-none rounded-lg border border-gray-200 text-white hover:bg-[#25d366] bg-emerald-600 focus:z-10 focus:ring-4 focus:ring-gray-100 justify-center cursor-pointer"
    target="_blank"
  >
    <svg
      class="mr-2 w-4 h-4 xl:w-6 xl:h-6 text-white"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="1.5"
      stroke="currentColor"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"
      />
    </svg>

    <span class="hidden md:block text-base">Написать в whatsapp</span>
    <span class="block md:hidden text-base">Написать в WA</span>
  </a>
</template>
