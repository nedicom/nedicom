<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import { Link } from '@inertiajs/inertia-vue3';
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref } from "vue";
import { ModalsContainer, useModal } from 'vue-final-modal'
import DeleteModalConfirm from '@/Components/DeleteModalConfirm.vue'
import { Inertia } from "@inertiajs/inertia";

let modalTitle = ref(null);
let userId = ref(null);

const { open, close } = useModal({
  component: DeleteModalConfirm,
  attrs: {
    title: modalTitle,
    id: userId,
    onConfirm(id) {
      Inertia.get(`/admin/users/${id}/delete`)
    },
    onClose() {
      close()
    }
  },
  slots: {
    default: '<p>UseModal: The content of the modal</p>',
  },
})

defineProps({
  users: "Array",
  filters: "Object",
  auth: Object,
});

const handleDelete = (id, title) => {
  userId.value = id
  modalTitle.value = title
  open()
}

</script>

<template>

  <Head title="Пользователи" />

  <MainHeader :auth="auth" />

  <ModalsContainer />

  <Body>
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center mr-4 w-full max-w-md">
              <div class="flex w-full bg-white rounded shadow">
                <input v-model="form.search" class="relative px-6 py-1 w-full rounded" autocomplete="off" type="text"
                  name="search" placeholder="Поиск...">

              </div>
              <input v-model="form.lawyer" id="lawyer" type="checkbox" value=""
                  class="w-4 h-4 ml-5 mr-2 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                  required /><div class="text-gray-500 text-sm">юрист</div>
              <button @click="reset" class="ml-5 text-gray-500 hover:text-gray-900 focus:text-indigo-500 text-sm"
                type="button">Сбросить</button>
            </div>
          </div>
          <div v-if="users.total > 0">
            <div class="bg-white rounded-md shadow overflow-x-auto">
              <table class="w-full whitespace-nowrap">
                <thead>
                  <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">ID</th>
                    <th class="pb-4 pt-6 px-6">Имя</th>
                    <th class="pb-4 pt-6 px-6">Email</th>
                    <th class="pb-4 pt-6 px-6">Администратор</th>
                    <th class="pb-4 pt-6 px-6">Юрист</th>
                    <th class="pb-4 pt-6 px-6">Дата создания</th>
                    <th class="pb-4 pt-6 px-6"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="users in users.data" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                      <Link class="flex items-center px-6 py-4" :href="`/admin/users/${users.id}/edit`">{{ users.id }}
                      </Link>
                    </td>
                    <td class="border-t">
                      <Link class="flex items-center px-6 py-4" :href="`/admin/users/${users.id}/edit`">{{ users.name }}
                      </Link>
                    </td>
                    <td class="border-t">
                      <Link class="flex items-center px-6 py-4" :href="`/admin/users/${users.id}/edit`">{{ users.email
                      }}</Link>
                    </td>
                    <td class="border-t">
                      <Link class="flex items-center px-6 py-4" :href="`/admin/users/${users.id}/edit`">{{ users.isadmin
                      ? 'Да' : 'Нет' }}</Link>
                    </td>
                    <td class="border-t">
                      <Link class="flex items-center px-6 py-4" :href="`/admin/users/${users.id}/edit`">{{ users.lawyer
                      ? 'Да' : 'Нет' }}</Link>
                    </td>
                    <td class="border-t">
                      <span class="flex items-center px-6 py-4">{{ users.created_at }}</span>
                    </td>
                    <td class="border-t">
                      <button @click="handleDelete(users.id, users.name)" type="button"
                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-1 text-center inline-flex items-center mr-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-5 h-5 fill-red-700">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- row -->
          <div v-else class="flex justify-center h-96">
            <div class="block p-6 rounded-lg shadow-lg bg-white max-w-sm">
              <h5 class="text-gray-900 text-xl leading-tight font-medium mb-2">
                Статей пока нет
              </h5>
              <p class="text-gray-700 text-base mb-2">
                Самое время
                <a :href="route('articles.add')" class="
                    basis-2/12
                    text-end              
                    text-blue-500
                    underline
                    dark:text-blue-500
                    hover:no-underline
                  ">начать статью</a>
              </p>
            </div>
          </div>
          <!-- row -->
          <Pagination :links="users.links" />
        </div>
      </div>
    </div>
  </Body>
  <MainFooter />
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
export default {
  components: {
    Pagination,
  },

  data() {
    return {
      form: {
        search: this.filters.search,
        lawyer: this.filters.lawyer,
      }
    }
  },

  watch: {
    form: {
      deep: true,
      handler: function (value) {
        this.$inertia.get('/admin/users', { search: this.form.search, lawyer: this.form.lawyer,  })
      },
    },
  },

  methods: {
    reset() {
      this.form.search = null,
      this.form.lawyer = null
    },
  },
};
</script>

<style lang="css">
.btn-indigo {
  @apply px-6 py-3 rounded bg-indigo-600 text-white text-sm leading-4 font-bold whitespace-nowrap hover:bg-orange-400 focus:bg-orange-400;
}

.btn-spinner,
.btn-spinner:after {
  border-radius: 50%;
  width: 1.5em;
  height: 1.5em;
}

.btn-spinner {
  font-size: 10px;
  position: relative;
  text-indent: -9999em;
  border-top: 0.2em solid white;
  border-right: 0.2em solid white;
  border-bottom: 0.2em solid white;
  border-left: 0.2em solid transparent;
  transform: translateZ(0);
  animation: spinning 1s infinite linear;
}

@keyframes spinning {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.form-label {
  @apply mb-2 block text-gray-700 select-none;
}

.form-input,
.form-textarea,
.form-select {
  @apply p-2 leading-normal block w-full border text-gray-700 bg-white font-sans rounded text-left appearance-none relative focus:border-indigo-400 focus:ring;

  &::placeholder {
    @apply text-gray-500 opacity-100;
  }
}

.form-select {
  @apply pr-6;

  background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAQCAYAAAAMJL+VAAAABGdBTUEAALGPC/xhBQAAAQtJREFUOBG1lEEOgjAQRalbGj2OG9caOACn4ALGtfEuHACiazceR1PWOH/CNA3aMiTaBDpt/7zPdBKy7M/DCL9pGkvxxVp7KsvyJftL5rZt1865M+Ucq6pyyF3hNcI7Cuu+728QYn/JQA5yKaempxuZmQngOwEaYx55nu+1lQh8GIatMGi+01NwBcEmhxBqK4nAPZJ78K0KKFAJmR3oPp8+Iwgob0Oa6+TLoeCvRx+mTUYf/FVBGTPRwDkfLxnaSrRwcH0FWhNOmrkWYbE2XEicqgSa1J0LQ+aPCuQgZiLnwewbGuz5MGoAhcIkCQcjaTBjMgtXGURMVHC1wcQEy0J+Zlj8bKAnY1/UzDe2dbAVqfXn6wAAAABJRU5ErkJggg==');
  background-size: 0.7rem;
  background-repeat: no-repeat;
  background-position: right 0.7rem center;

  &::-ms-expand {
    @apply opacity-0;
  }
}

.form-input.error,
.form-textarea.error,
.form-select.error {
  @apply border-red-500 focus:ring focus:ring-red-200;
}

.form-error {
  @apply text-red-700 mt-2 text-sm;
}
</style>
