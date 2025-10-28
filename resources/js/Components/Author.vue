<script setup>
import { ref, reactive, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/inertia-vue3";

const authorSearch = ref("");
const isSearching = ref(false);
const searchResults = ref([]);
const selectedAuthor = ref(null);
const searchError = ref("");

const props = defineProps({
  uslugaid: Number,
  user_id: Number,
});

const page = usePage();
const csrfToken = page.props.value.csrf_token; // CSRF токен из Inertia

const authorForm = reactive({
  author_id: null,
  usluga_id: props.uslugaid,
});

// Поиск с задержкой
let searchTimeout = null;
watch(authorSearch, (newValue) => {
  if (searchTimeout) clearTimeout(searchTimeout);

  if (newValue.length >= 2) {
    searchTimeout = setTimeout(() => {
      searchAuthors();
    }, 300);
  } else {
    searchResults.value = [];
  }
});

// Функция поиска автора через fetch
async function searchAuthors() {
  if (!authorSearch.value.trim()) {
    searchResults.value = [];
    return;
  }

  isSearching.value = true;
  searchError.value = "";

  try {
    const response = await fetch(
      `/api/search-author?q=${encodeURIComponent(authorSearch.value)}`,
      {
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": csrfToken, // Используем токен из Inertia
        },
      }
    );

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    searchResults.value = data;
  } catch (error) {
    console.error("Ошибка поиска автора:", error);
    searchError.value = "Ошибка при поиске автора";
    searchResults.value = [];
  } finally {
    isSearching.value = false;
  }
}

// Остальные функции без изменений
function selectAuthor(author) {
  selectedAuthor.value = author;
  authorForm.author_id = author.id;
  authorSearch.value = author.name;
  searchResults.value = [];
  searchError.value = "";
}

function clearAuthor() {
  selectedAuthor.value = null;
  authorForm.author_id = null;
  authorSearch.value = "";
  searchError.value = "";
}

function changeAuthor() {
  if (!authorForm.author_id) {
    alert("Пожалуйста, выберите автора");
    return;
  }

  Inertia.post("/change-author", authorForm, {
    preserveScroll: true,
    onSuccess: () => {
      clearAuthor();
    },
  });
}
</script>

<template>
  <span>
    <p class="font-semibold mt-5">Смена автора</p>
    <p class="text-sm mb-3 text-gray-600">
      Найдите автора по имени или email и получите его ID
    </p>
    <p class="text-xs text-gray-500 mb-2">
      Usluga ID: {{ uslugaid }}, Author ID: {{ props.user_id }}
    </p>

    <form @submit.prevent="changeAuthor" class="grid grid-cols-1 gap-4">
      <!-- Поиск автора -->
      <div class="mb-5">
        <label
          for="authorSearch"
          class="block text-sm font-medium leading-6 text-gray-900"
        >
          Поиск автора (имя или email) - минимум 2 символа
        </label>
        <div class="relative">
          <input
            v-model="authorSearch"
            type="text"
            name="authorSearch"
            class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
            placeholder="Введите имя или email автора"
          />

          <!-- Индикатор загрузки -->
          <div v-if="isSearching" class="absolute right-3 top-2">
            <div
              class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"
            ></div>
          </div>
        </div>

        <!-- Сообщение об ошибке -->
        <div v-if="searchError" class="text-red-600 text-sm mt-1">
          {{ searchError }}
        </div>

        <!-- Результаты поиска -->
        <div
          v-if="searchResults.length > 0"
          class="mt-2 border border-gray-300 rounded-md max-h-32 overflow-y-auto"
        >
          <div
            v-for="author in searchResults"
            :key="author.id"
            @click="selectAuthor(author)"
            class="p-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0"
          >
            <div class="font-medium">{{ author.name }}</div>
            <div class="text-xs text-gray-500">{{ author.email }}</div>
            <div class="text-xs text-gray-400">ID: {{ author.id }}</div>
          </div>
        </div>

        <!-- Выбранный автор -->
        <div
          v-if="selectedAuthor"
          class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md"
        >
          <div class="flex justify-between items-center">
            <div>
              <div class="font-medium text-green-800">Выбран автор:</div>
              <div class="text-sm">{{ selectedAuthor.name }}</div>
              <div class="text-xs text-green-600">
                Email: {{ selectedAuthor.email }}
              </div>
              <div class="text-xs text-green-600">
                ID: {{ selectedAuthor.id }}
              </div>
            </div>
            <button
              type="button"
              @click="clearAuthor"
              class="text-red-600 hover:text-red-800 text-sm font-bold text-lg"
            >
              ×
            </button>
          </div>
        </div>
      </div>

      <!-- Кнопка смены автора -->
      <div class="flex items-center justify-center">
        <button
          type="submit"
          :disabled="!authorForm.author_id"
          :class="[
            'my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900',
            authorForm.author_id
              ? 'bg-blue-700 hover:bg-blue-800'
              : 'bg-gray-400 cursor-not-allowed',
          ]"
        >
          Сменить автора
        </button>
      </div>
    </form>
  </span>
</template>