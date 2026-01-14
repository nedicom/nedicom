<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Editor from "@/Components/Tiptap.vue";
import Practicecropper from "@/Components/Practicecropper.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref, watch, reactive, onMounted, onUnmounted } from "vue";

const autosave = ref(true);
const recentlySuccessful = ref(false);
const savedelay = ref(false);
const lawyerSearch = ref('');
const searchResults = ref([]);
const isLoading = ref(false);
const showDropdown = ref(false);
const currentAuthor = ref(null);
const selectedLawyer = ref(null);
const searchError = ref(null);

let set = defineProps({
  article: Object,
  uslugi: Object,
  auth: Object,
  region: Object,
  status: String,
});

let form = reactive({
  header: set.article.header,
  description: set.article.description,
  body: set.article.body,
  usluga_id: set.article.usluga_id,
  region: set.article.region,
  id: set.article.id,
  youtube: set.article.youtube_file_path,
  avito: set.article.avito,
  tg: set.article.tg,
  tg_description: set.article.tg_description,
  userid: set.article.userid,
});

// Инициализация текущего автора
onMounted(() => {
  if (set.article.userid && set.article.username) {
    currentAuthor.value = {
      id: set.article.userid,
      name: set.article.username,
      email: ''
    };
    selectedLawyer.value = currentAuthor.value;
    form.userid = set.article.userid;
  }

  // Добавляем обработчик клика вне dropdown
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

// Дебаунс для поиска
let searchTimeout = null;
const DEBOUNCE_DELAY = 500;

// Метод поиска юристов через fetch
const searchLawyers = () => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchError.value = null;
  
  searchTimeout = setTimeout(async () => {
    const searchTerm = lawyerSearch.value.trim();
    
    if (searchTerm.length < 2) {
      searchResults.value = [];
      showDropdown.value = false;
      return;
    }
    
    isLoading.value = true;
    showDropdown.value = true;
    
    try {
      const token = document.querySelector('meta[name="csrf-token"]')?.content;
      
      const response = await fetch(`/search-lawyers-web?search=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': token || '',
        },
        credentials: 'same-origin',
      });
      
      if (!response.ok) {
        const errorText = await response.text();
        console.error('Error response:', errorText);
        
        if (response.status === 401) {
          searchError.value = 'Требуется авторизация';
        } else if (response.status === 403) {
          searchError.value = 'Только администратор может менять автора';
        } else {
          searchError.value = `Ошибка ${response.status}: ${errorText}`;
        }
        searchResults.value = [];
        return;
      }
      
      const data = await response.json();
      searchResults.value = data;
    } catch (error) {
      console.error('Ошибка поиска юристов:', error);
      searchError.value = 'Ошибка соединения';
      searchResults.value = [];
    } finally {
      isLoading.value = false;
    }
  }, DEBOUNCE_DELAY);
};

// Выбор юриста из списка
const selectLawyer = (lawyer) => {
  selectedLawyer.value = lawyer;
  form.userid = lawyer.id;
  lawyerSearch.value = lawyer.name;
  showDropdown.value = false;
  searchResults.value = [];
  searchError.value = null;
};

// Очистка выбора
const clearSelection = () => {
  if (currentAuthor.value) {
    selectedLawyer.value = currentAuthor.value;
    form.userid = currentAuthor.value.id;
  } else {
    selectedLawyer.value = null;
    form.userid = null;
  }
  lawyerSearch.value = '';
  searchResults.value = [];
  showDropdown.value = false;
  searchError.value = null;
};

// Клик вне dropdown
const handleClickOutside = (event) => {
  const dropdown = document.querySelector('.search-dropdown-container');
  const input = document.querySelector('.lawyer-search-input');

  if (dropdown && input && !dropdown.contains(event.target) && !input.contains(event.target)) {
    showDropdown.value = false;
  }
};

// Автосохранение и остальной код...
let timerId = null;

watch(() => form.body, () => {
  if (autosave.value) {
    if (!savedelay.value) {
      savedelay.value = true;
      timerId = setTimeout(() => {
        recentlySuccessful.value = true;
        Inertia.post("/articles/" + set.article.id + "/autoupdate", form, {
          preserveScroll: true,
          onSuccess: () => setTimeout(() => {
            recentlySuccessful.value = false;
            savedelay.value = false;
          }, 3000)
        })
      }, 30000);
    }
  }
})

function submit() {
  if (set.article.id) {
    clearTimeout(timerId);
    Inertia.post("/articles/update", form);
  }
}
</script>

<template>

  <Head title="Редактировать статью" />
  <MainHeader :auth="set.auth" />

  <Body>
    <div class="bg-white py-12 m-3">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <form @submit.prevent="submit">
            <div class="grid gap-4 md:grid-cols-2">
              <div class="">
                <input v-model="form.id" class="invisible" />

                <!-- Блок смены автора (только для админа) -->
                <div v-if="set.auth && set.auth.isadmin"
                  class="my-5 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                  <h3 class="text-lg font-semibold text-yellow-800 mb-2">Административные настройки</h3>

                  <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-yellow-700">
                      Сменить автора статьи
                      <span class="text-xs text-yellow-600 font-normal ml-2">
                        (введите ФИО или email для поиска)
                      </span>
                    </label>

                    <!-- Отображение текущего выбора -->
                    <div v-if="selectedLawyer" class="mb-3 p-3 bg-white border border-yellow-100 rounded-lg">
                      <div class="flex justify-between items-center">
                        <div>
                          <p class="font-medium text-gray-800">{{ selectedLawyer.name }}</p>
                          <p class="text-xs text-gray-500" v-if="selectedLawyer.email">
                            {{ selectedLawyer.email }}
                          </p>
                          <p class="text-xs text-yellow-600 mt-1">
                            ID: {{ selectedLawyer.id }}
                            <span v-if="selectedLawyer.id === set.article.userid"
                              class="ml-2 bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">
                              Текущий автор
                            </span>
                          </p>
                        </div>
                        <button type="button" @click="clearSelection" class="text-red-500 hover:text-red-700 text-sm">
                          Сбросить
                        </button>
                      </div>
                    </div>

                    <!-- Поле поиска -->
                    <div class="relative search-dropdown-container">
                      <div class="flex items-center">
                        <div class="relative flex-grow">
                          <input type="text" v-model="lawyerSearch" @input="searchLawyers" @focus="showDropdown = true"
                            placeholder="Начните вводить ФИО или email..."
                            class="w-full p-3 pl-10 text-sm border border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition lawyer-search-input"
                            :disabled="isLoading">

                          <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <svg v-if="isLoading" class="animate-spin h-4 w-4 text-yellow-500"
                              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                              </circle>
                              <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                              </path>
                            </svg>
                            <svg v-else class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor"
                              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                          </div>

                          <!-- Результаты поиска -->
                          <div v-if="showDropdown"
                            class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <!-- Сообщение об ошибке -->
                            <div v-if="searchError" class="p-3 text-red-600 text-sm bg-red-50 border-b">
                              {{ searchError }}
                            </div>

                            <!-- Сообщение о вводе -->
                            <div v-if="lawyerSearch.length < 2 && lawyerSearch.length > 0 && !searchError"
                              class="p-3 text-yellow-600 text-sm">
                              Введите хотя бы 2 символа для поиска
                            </div>

                            <!-- Сообщение "ничего не найдено" -->
                            <div
                              v-if="lawyerSearch.length >= 2 && searchResults.length === 0 && !isLoading && !searchError"
                              class="p-3 text-gray-500 text-sm">
                              Ничего не найдено. Попробуйте другой запрос.
                            </div>

                            <!-- Список результатов -->
                            <ul v-if="searchResults.length > 0" class="py-1">
                              <li v-for="lawyer in searchResults" :key="lawyer.id" @click="selectLawyer(lawyer)"
                                class="px-4 py-3 hover:bg-yellow-50 cursor-pointer transition-colors border-b border-gray-100 last:border-b-0">
                                <div class="flex justify-between items-start">
                                  <div>
                                    <p class="font-medium text-gray-800">{{ lawyer.name }}</p>
                                    <p class="text-xs text-gray-500">{{ lawyer.email }}</p>
                                  </div>
                                  <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded">
                                    ID: {{ lawyer.id }}
                                  </span>
                                </div>
                                <div v-if="lawyer.id === set.article.userid"
                                  class="mt-1 text-xs text-yellow-700 bg-yellow-50 px-2 py-1 rounded inline-block">
                                  Текущий автор статьи
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>

                      <p class="mt-2 text-xs text-yellow-600">
                        Текущий автор: <span class="font-medium">{{ set.article.username }}</span> (ID: {{
                        set.article.userid }})
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Остальная форма -->
                <div class="my-5">
                  <div class="flex items-center mb-4 justify-between">
                    <div>
                      <input id="default-checkbox" type="checkbox" v-model="autosave" :disabled="!set.auth"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2">
                      <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900">
                        Автосохранение (каждые 30 секунд)
                      </label>
                    </div>
                    <Transition enter-from-class="opacity-0" leave-to-class="opacity-0"
                      class="transition ease-in-out ml-5">
                      <p v-if="recentlySuccessful" class="text-sm text-green-600">
                        Сохранено
                      </p>
                    </Transition>
                  </div>
                </div>

                <textarea v-model="form.header" @input="onInputheader" spellcheck="true" name="header" maxlength="55"
                  class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                  rows="2" required></textarea>
                <div class="my-1 w-full bg-gray-200 rounded-full h-1 dark:bg-gray-700">
                  <div class="h-1 rounded-full" :class="headProgressColor" :style="{ width: progresswidth + '%' }">
                  </div>
                </div>
                <p class="text-xs text-gray-900 dark:text-white">
                  Символов: {{ wordscounter }} Макс: 55 Рекомендовано: до 55
                </p>

                <textarea v-model="form.description" @input="onInputdesc" maxlength="200" spellcheck="true"
                  class="h-28 form-control mt-3 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                  required></textarea>
                <div class="my-1 w-full bg-gray-200 rounded-full h-1 dark:bg-gray-700">
                  <div class="h-1 rounded-full" :class="progressColor" :style="{ width: progressdescwidth + '%' }">
                  </div>
                </div>
                <p class="text-xs text-gray-900 dark:text-white">
                  Символов: {{ wordsdesccounter }} Макс: 260 Рекомендовано: от 80 до 160
                </p>

                <div class="flex">
                  <div class="w-1/2 pr-1">
                    <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">
                      Выберите категорию
                    </label>
                    <select v-model="form.usluga_id"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                      <option disabled value="">Выберите один из вариантов</option>
                      <option v-for="option in set.uslugi" :key="option.id" v-bind:value="option.id">
                        {{ option.usl_name }}
                      </option>
                    </select>
                  </div>

                  <div class="w-1/2 pl-1">
                    <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">
                      Выберите регион
                    </label>
                    <select v-model="form.region"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                      <option v-for="option in set.region" :key="option.regionid" v-bind:value="option.regionid">
                        {{ option.region }}
                      </option>
                    </select>
                  </div>
                </div>

                <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Ссылка на Услугу
                </label>
                <input v-model="form.avito"
                  class="form-control mt-3 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" />
                <p class="mt-2 text-sm">
                  <span class="font-medium">Такая ссылка увеличивает поток Ваших клиентов.</span>
                  Просто скопируйте ссылку на Ваш профиль или объявление. Можно вставить ссылку на Авито.
                </p>

                <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Ссылка на телеграм
                </label>
                <input v-model="form.tg"
                  class="form-control mt-3 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" />
                <p class="mt-2 text-sm">
                  <span class="font-medium">Такая ссылка увеличивает число заявок или подписчиков.</span>
                  Просто скопируйте ссылку на ТГ канал или в чат.
                </p>

                <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Описание телеграм
                </label>
                <input v-model="form.tg_description"
                  class="form-control mt-3 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" />
                <p class="mt-2 text-sm">
                  <span class="font-medium">Расскажите пользователю зачем он должен перейти в Ваш телеграм</span>
                </p>

                <label for="post" class="block mb-1 mt-5 text-xs font-medium text-gray-700">
                  Ваш пост будет шикарен
                </label>
                <editor spellcheck="true" v-model="form.body" :auth="set.auth" :imgurl="$page.props.flash"
                  :id="set.article.userid" :type="'article'" id="post" />

                <div class="flex justify-center">
                  <button type="submit"
                    class="my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800 text-center">
                    Обновить
                  </button>
                </div>
              </div>

              <div class="hidden md:contents">
                <div class="bg-gray-50 text-gray-800">
                  <h3 class="text-2xl font-bold p-3">Яндекс</h3>
                  <div class="rounded-lg shadow-lg bg-white p-5 mx-5">
                    <h5 class="text-blue-800 text-xl line-clamp-2 leading-tight font-medium hover:text-red-400">
                      {{ yaheader }}
                    </h5>
                    <a href="#" class="text-green-700 hover:text-red-400 transition duration-300 ease-in-out mb-4">
                      https://nedicom.ru/<span>{{ url }}</span>
                    </a>
                    <p class="text-gray-700 line-clamp-3 text-base text-ellipsis">
                      {{ yabody }}
                    </p>
                  </div>
                  <h3 class="text-2xl font-bold p-3 mt-5">Google</h3>
                  <div class="rounded-lg shadow-lg bg-white p-5 mx-5">
                    <a href="#" class="transition duration-300 ease-in-out">
                      https://nedicom.ru/<span>{{ url }}</span>
                    </a>
                    <h5
                      class="text-blue-700 no-underline line-clamp-2 hover:underline text-xl leading-tight font-medium my-1">
                      {{ gooheader }}
                    </h5>
                    <p class="text-gray-700 text-base line-clamp-3 text-ellipsis overflow-hidden">
                      {{ goobody }}
                    </p>
                  </div>
                  <div class="text-center text-xs px-10">
                    <p>тут немного технической информации. не обращайте внимания, скоро мы ее уберем</p>
                    <p>{{ savedelay }} задержка автосохранения savedelay</p>
                    <p>{{ recentlySuccessful }} кнопка сохранения recentlySuccessful</p>
                    <p> {{ autosave }} автосохр autosave</p>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>

        <Practicecropper :article="article" />
      </div>
    </div>
  </Body>
  <MainFooter />
</template>

<script>
export default {
  data() {
    return {
      yaheader: "Заголовок идеальной статьи в Яндексе. До 55 символов",
      yabody: "Описание идеальной статьи в Яндексе. Яндекс любит краткое описание до 160 символов на компьютере, и до 80 символов на экране мобильного.",
      gooheader: "Заголовок идеальной статьи в Google",
      goobody: "Описание идеальной статьи в Google. Google тоже предпочитает до 160 символов на десктопе и до 80 на мобильном.",
      progresswidth: 0,
      wordscounter: 0,
      progressdescwidth: 0,
      wordsdesccounter: 0,
      titletext: "sometest",
      headProgressColor: "",
      progressColor: "",
      url: "адрес статьи",
    };
  },
  methods: {
    onInputheader(e) {
      this.yaheader = this.gooheader = e.target.value;
      this.wordscounter = this.yaheader.length;
      if (this.yaheader.length <= 55) {
        this.progresswidth = (100 / 55) * this.yaheader.length;
      }

      if (e.target.value.length < 20) {
        this.url = e.target.value;
      }

      if (this.yaheader.length > 0) {
        this.headProgressColor = "bg-gray-300";
      }
      if (this.yaheader.length > 40) {
        this.headProgressColor = "bg-blue-600";
      }
    },
    onInputdesc(e) {
      this.yabody = e.target.value;
      this.goobody = e.target.value;
      this.wordsdesccounter = this.yabody.length;
      if (this.yabody.length <= 260) {
        this.progressdescwidth = (100 / 260) * this.yabody.length;
      }
      if (this.yabody.length > 0) {
        this.progressColor = "bg-gray-300";
      }
      if (this.yabody.length > 60) {
        this.progressColor = "bg-blue-600";
      }
      if (this.yabody.length > 160) {
        this.progressColor = "bg-red-50 0";
      }
    },
  },
};
</script>

<style scoped>
/* Стили для скроллбара */
.max-h-60::-webkit-scrollbar {
  width: 6px;
}

.max-h-60::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.max-h-60::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 3px;
}

.max-h-60::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Анимации */
.transition-colors {
  transition: background-color 0.2s ease;
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}
</style>