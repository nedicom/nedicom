<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { ModalsContainer, useModal } from "vue-final-modal";
import CitySet from "@/Components/CitySet.vue";


const showingNavigationDropdown = ref(false);

let props = defineProps({
  auth: Object,
  city: Object,
  profile: Boolean,
  hideBtn: Boolean,
  reloadpage: Boolean,
  mainurl: [Number, String],
  secondurl: String,
  backendurl: String,
  tracking: {
    type: Object,
    default: () => ({})
  }
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


</script>

<template>
  <div class="mh" id="mh">
    <div class="bg-gray-100">
      <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="flex items-center">
                <Link :href="route('Welcome')" class="flex justify-center items-center h-20 pt-3" aria-label="Home">
                  <ApplicationLogo class="block w-auto fill-current text-gray-800" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:ml-5 lg:ml-10 sm:flex">
                <NavLink :href="route('Welcome')" :active="route().current('Welcome')">
                  Главная
                </NavLink>
              </div>
              <div class="hidden space-x-8 sm:ml-5 lg:ml-10 sm:flex">
                <NavLink :href="route('lenta.popular')" :active="route().current('lenta.popular')">
                  Лента
                </NavLink>
              </div>
              <div class="hidden space-x-8 sm:ml-5 lg:ml-10 sm:flex">
                <NavLink :href="route('questions.add')" :active="route().current('questions.add')">
                  Задать вопрос
                </NavLink>
              </div>
              <div class="hidden space-x-8 sm:ml-5 lg:ml-10 sm:flex">
                <NavLink :href="route('uslugi')" :active="route().current('uslugi')">
                  Найти юриста
                </NavLink>
              </div>
              <div class="hidden space-x-8 sm:ml-5 lg:ml-10 sm:flex">
                <NavLink :href="route('clientdashboard')" :active="route().current('clientdashboard')">
                  Кабинет клиента
                </NavLink>
              </div>
              <div class="hidden space-x-8 sm:ml-5 lg:ml-10 sm:flex">
                <NavLink :href="route('lawyerdashboard')" :active="route().current('lawyerdashboard')">
                  Кабинет юриста
                </NavLink>
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <!-- Settings Dropdown -->
              <div class="ml-3 relative flex items-center">
                <div v-if="props.auth" class="flex gap-2 h-7">
                  <a v-if="props.auth.lawyer == 1" type="button" href="/uslugiadd"
                    class="px-3 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 flex items-center"><svg
                      xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" class="size-5">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="ml-1">объявление</span></a>

                  <a type="button" href="/articlesadd"
                    class="px-3 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 flex items-center"><svg
                      xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" class="size-5">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="ml-1">пост</span></a>
                </div>

                <button v-if="!hideBtn" type="button" @click="() => open()" aria-label="city"
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
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
                      <button type="button" aria-label="enter"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <span v-if="props.auth !== null">{{
                          props.auth.name
                        }}</span>

                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                          fill="currentColor">
                          <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <div>
                      <DropdownLink :href="route('lenta.bookmarked')">
                        Закладки
                      </DropdownLink>
                      <DropdownLink v-if="props.auth.lawyer == 1" :href="route('my')">
                        Мой кабинет
                      </DropdownLink>
                      <DropdownLink v-if="props.auth.lawyer == 0" :href="route('my.questions')">
                        Мои вопросы
                      </DropdownLink>

                      <DropdownLink v-if="props.auth.isadmin" :href="route('admin.articles.list')">
                        Все статьи
                      </DropdownLink>

                      <DropdownLink v-if="props.auth.isadmin" :href="route('admin.uslugi.list')">
                        Все услуги
                      </DropdownLink>

                      <DropdownLink v-if="props.auth.isadmin" :href="route('messages')">
                        Сообщения
                      </DropdownLink>
                      <DropdownLink v-if="props.auth.isadmin" :href="route('article.generator')">
                        Генератор
                      </DropdownLink>
                      <DropdownLink :href="route('profile.edit')">
                        Профиль
                      </DropdownLink>
                      <DropdownLink :href="route('logout')" method="post" as="button">Выйти</DropdownLink>
                    </div>
                  </template>
                </Dropdown>

                <button v-if="!props.auth" type="button"
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                  <Link :href="route('login')"> Войти </Link>
                </button>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button title="Mainmenu" @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path :class="{
                    hidden: showingNavigationDropdown,
                    'inline-flex': !showingNavigationDropdown,
                  }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  <path :class="{
                    hidden: !showingNavigationDropdown,
                    'inline-flex': showingNavigationDropdown,
                  }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{
          block: showingNavigationDropdown,
          hidden: !showingNavigationDropdown,
        }" class="sm:hidden">
          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('Welcome')" :active="route().current('Welcome')">
              Главная
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('lenta.popular')" :active="route().current('lenta.popular')">
              Лента
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('questions.add')" :active="route().current('questions.add')">
              Задать вопрос
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('uslugi')" :active="route().current('uslugi')">
              Найти юриста
            </ResponsiveNavLink>

            <ResponsiveNavLink :href="route('clientdashboard')" :active="route().current('clientdashboard')">
              Кабинет клиента
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('lawyerdashboard')" :active="route().current('lawyerdashboard')">
              Кабинет юриста
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

              <ResponsiveNavLink v-if="props.auth.lawyer == 1" :href="route('my')">
                Мой кабинет
              </ResponsiveNavLink>
              <ResponsiveNavLink :href="route('logout')" method="post" as="button">
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

  <div v-if="!props.auth && $page.props.cookie == false"
    class="fixed bottom-0 left-0 flex items-center text-gray-500 bg-white rounded-lg z-50 shadow-lg p-5" role="alert">
    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg">
      <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z" />
      </svg>
      <span class="sr-only">Fire icon</span>
    </div>
    <div class="ms-3 text-sm font-normal w-full">
      Пользуясь сайтом, вы принимаете политику
      <a href="/cookie" class="text-blue-400 underline">cookie</a>
    </div>
    <button type="button" @click="Inertia.post(route('cookie'), true, { preserveScroll: true })"
      class="mx-2 bg-gray-100 text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-200 inline-flex items-center justify-center">
      Хорошо
    </button>
  </div>
</template>