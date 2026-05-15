<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/inertia-vue3";
import CookieConsent from '@/Components/CookieConsent.vue'
import { ModalsContainer, useModal } from "vue-final-modal";
import CitySet from "@/Components/CitySet.vue";
import ModalPhoneConfirm from "@/Components/ModalPhoneConfirm.vue";

const showingNavigationDropdown = ref(false);
const cabinetOpen = ref(false);

const headerPhone = "+79788838978";
const headerPhoneTo = "tel:+79788838978";

let props = defineProps({
  auth: Object,
  city: Object,
  profile: Boolean,
  hideBtn: Boolean,
  reloadpage: Boolean,
  mainurl: [Number, String],
  secondurl: String,
  backendurl: String,
  showPhone: {
    type: Boolean,
    default: false,
  },
  tracking: {
    type: Object,
    default: () => ({}),
  },
});

const { open, close } = useModal({
  component: CitySet,
  attrs: {
    profile: props.profile,
    mainurl: props.mainurl,
    secondurl: props.secondurl,
    modalPageTitle: "city",
    reloadpage: props.reloadpage,
    onConfirm() {
      close();
    },
  },
});

defineExpose({ open });

const { open: openPhone, close: closePhone } = useModal({
  component: ModalPhoneConfirm,
  attrs: {
    phone: headerPhone,
    phoneto: headerPhoneTo,
    onClose() { closePhone(); },
  },
});
</script>

<template>
  <div class="mh sticky top-0 z-50" id="mh">
    <div class="bg-gray-100">
      <nav class="bg-white border-b border-gray-100 shadow-sm">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-12">
            <div class="flex">
              <!-- Logo -->
              <div class="flex items-center">
                <Link
                  :href="route('Welcome')"
                  class="flex justify-center items-center h-12 pt-0"
                  aria-label="Home"
                >
                  <ApplicationLogo
                    class="block w-auto fill-current text-gray-800"
                  />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden sm:flex items-center ml-4 lg:ml-6 gap-1 lg:gap-3">
                <NavLink :href="route('Welcome')" :active="route().current('Welcome')">
                  <span class="whitespace-nowrap">Главная</span>
                </NavLink>
                <NavLink :href="route('lenta.popular')" :active="route().current('lenta.popular')">
                  <span class="whitespace-nowrap">Лента</span>
                </NavLink>
                <NavLink :href="route('questions.add')" :active="route().current('questions.add')">
                  <span class="whitespace-nowrap">Задать вопрос</span>
                </NavLink>
                <NavLink :href="route('uslugi')" :active="route().current('uslugi')">
                  <span class="whitespace-nowrap">Найти юриста</span>
                </NavLink>
                <div class="hidden lg:block relative">
                  <button
                    type="button"
                    @click="cabinetOpen = !cabinetOpen"
                    class="inline-flex items-center gap-1 px-1 pt-1 border-b-2 border-transparent text-sm leading-4 text-gray-500 hover:text-gray-700 focus:outline-none transition whitespace-nowrap"
                  >
                    Кабинет
                  </button>
                  <div v-show="cabinetOpen" class="fixed inset-0 z-40" @click="cabinetOpen = false"></div>
                  <div
                    v-show="cabinetOpen"
                    class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 py-1"
                  >
                    <a :href="route('clientdashboard')" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      Кабинет клиента
                    </a>
                    <a :href="route('lawyerdashboard')" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                      </svg>
                      Кабинет юриста
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <!-- Phone button (only on pages that opt in) -->
              <button
                v-if="props.showPhone"
                onclick="ym(24900584, 'reachGoal', 'OPEN_PHONE');"
                @click="openPhone"
                class="mr-3 inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition-colors focus:outline-none whitespace-nowrap"
              >
                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                </svg>
                Позвонить
              </button>
              <!-- Settings Dropdown -->
              <div class="ml-3 relative flex items-center">
                <div v-if="props.auth" class="flex gap-2 h-7">
                  <a
                    v-if="props.auth.lawyer == 1"
                    type="button"
                    href="/uslugiadd"
                    class="px-3 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 flex items-center"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="size-5"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                      />
                    </svg>
                    <span class="ml-1">объявление</span></a
                  >

                  <a
                    type="button"
                    href="/articlesadd"
                    class="px-3 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 flex items-center"
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="size-5"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                      />
                    </svg>
                    <span class="ml-1">пост</span></a
                  >
                </div>

                <button
                  v-if="!hideBtn"
                  type="button"
                  @click="() => open()"
                  aria-label="city"
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                >
                  <span v-if="props.city"> {{ props.city.title }}</span>
                  <span v-else-if="props.auth">
                    <span v-if="props.auth.city != 0">{{
                      props.auth.city
                    }}</span>
                    <span v-else>город</span>
                  </span>
                  <span v-else>город</span>
                </button>

                <Dropdown align="right" width="48" v-if="props.auth">
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                        type="button"
                        aria-label="enter"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                      >
                        <span v-if="props.auth !== null">{{
                          props.auth.name
                        }}</span>

                        <svg
                          class="ml-2 -mr-0.5 h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <div>
                      <DropdownLink :href="route('lenta.bookmarked')">
                        Закладки
                      </DropdownLink>
                      <DropdownLink
                        v-if="props.auth.lawyer == 1"
                        :href="route('my')"
                      >
                        Мой кабинет
                      </DropdownLink>
                      <DropdownLink
                        v-if="props.auth.lawyer == 0"
                        :href="route('my.questions')"
                      >
                        Мои вопросы
                      </DropdownLink>

                      <DropdownLink
                        v-if="props.auth.isadmin"
                        :href="route('admin.articles.list')"
                      >
                        Все статьи
                      </DropdownLink>

                      <DropdownLink
                        v-if="props.auth.isadmin"
                        :href="route('admin.uslugi.list')"
                      >
                        Все услуги
                      </DropdownLink>

                      <DropdownLink
                        v-if="props.auth.isadmin"
                        :href="route('messages')"
                      >
                        Сообщения
                      </DropdownLink>
                      <DropdownLink
                        v-if="props.auth.isadmin"
                        :href="route('article.generator')"
                      >
                        Генератор
                      </DropdownLink>
                      <DropdownLink :href="route('profile.edit')">
                        Профиль
                      </DropdownLink>
                      <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                        >Выйти</DropdownLink
                      >
                    </div>
                  </template>
                </Dropdown>

                <button
                  v-if="!props.auth"
                  type="button"
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                >
                  <Link :href="route('login')"> Войти </Link>
                </button>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button
                title="Mainmenu"
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
              >
                <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
          :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }"
          class="sm:hidden"
        >
          <div v-if="props.showPhone" class="px-4 py-3">
            <button
              onclick="ym(24900584, 'reachGoal', 'OPEN_PHONE');"
              @click="openPhone"
              class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition-colors focus:outline-none"
            >
              <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
              </svg>
              Позвонить
            </button>
          </div>
          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink
              :href="route('Welcome')"
              :active="route().current('Welcome')"
            >
              Главная
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('lenta.popular')"
              :active="route().current('lenta.popular')"
            >
              Лента
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('questions.add')"
              :active="route().current('questions.add')"
            >
              Задать вопрос
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('uslugi')"
              :active="route().current('uslugi')"
            >
              Найти юриста
            </ResponsiveNavLink>

            <div class="px-4 pt-2 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-widest">Кабинет</div>
            <ResponsiveNavLink
              :href="route('clientdashboard')"
              :active="route().current('clientdashboard')"
            >
              — Кабинет клиента
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('lawyerdashboard')"
              :active="route().current('lawyerdashboard')"
            >
              — Кабинет юриста
            </ResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div class="pt-4 border-t border-gray-200">
            <div v-if="props.auth">
              <ResponsiveNavLink :href="route('profile.edit')">
                Профиль - {{ props.auth.name }}
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('my.questions')">
                Мои вопросы
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('lenta.bookmarked')">
                Закладки
              </ResponsiveNavLink>

              <ResponsiveNavLink
                v-if="props.auth.lawyer == 1"
                :href="route('my')"
              >
                Мой кабинет
              </ResponsiveNavLink>
              <ResponsiveNavLink
                :href="route('logout')"
                method="post"
                as="button"
              >
                Выйти
              </ResponsiveNavLink>
            </div>

            <div v-else>
              <ResponsiveNavLink :href="route('lenta.bookmarked')">
                Закладки
              </ResponsiveNavLink>
              <ResponsiveNavLink :href="route('login')" class="underline">
                Войти
              </ResponsiveNavLink>
            </div>
          </div>
        </div>
      </nav>
    </div>
  </div>
  <ModalsContainer />

  <CookieConsent :auth="props.auth" />
</template>