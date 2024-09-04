<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { ModalsContainer, useModal } from 'vue-final-modal'
import DeleteModalConfirm from '@/Components/DeleteModalConfirm.vue'
import { Inertia } from "@inertiajs/inertia";

let modalTitle = ref(null);
let userId = ref(null);
let title = ref("Мои услуги");

defineProps({
    uslugi: "Array",
    filters: "Object",
});


const { open, close } = useModal({
  component: DeleteModalConfirm,
  attrs: {
    title: modalTitle,
    id: userId,
    onConfirm(id) {
      Inertia.get(`/uslugi/${id}/delete`)
    },
    onClose() {
      close()
    }
  },
  slots: {
    default: '<p>UseModal: The content of the modal</p>',
  },
})

const handleDelete = (id, title) => {
  userId.value = id
  modalTitle.value = title
  open()
}


</script>

<template>
  <Head>
    <title>{{title}}</title>
    <meta name="description" content="Услуги юриста" />
  </Head>

  <MainHeader />

  <Header :ttl="title" />

  <ModalsContainer />

  <Body>
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex items-center justify-between mb-6">
            <div class="flex items-center mr-4 w-full max-w-md">
              <div class="flex w-full bg-white rounded shadow">
                <input
                  v-model="form.search"
                  class="relative px-6 py-1 w-full rounded"
                  autocomplete="off"
                  type="text"
                  name="search"
                  placeholder="поиск по названию..."
                >
              </div>
              <button @click="reset" class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm" type="button">обновить</button>
            </div>
            <Link
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
              href="/uslugiadd"
            >
              Новая услуга
            </Link>
        </div>
          
          <div v-if="uslugi.total > 0" class="grid gap-9">
              <!-- row -->
              <div
                v-for="uslugi in uslugi.data"
                :key="uslugi.id"
                class="flex justify-center"
              >
                <div
                  class="
                    flex flex-row                 
                    min-w-full
                    p-6
                    rounded-lg
                    shadow-lg
                    bg-white
                    max-w-sm
                  "
                >
                  <span class="basis-1/12">*</span>
                  <h5
                    class="
                      basis-9/12
                      text-left                    
                      text-gray-900 text-xl
                      leading-tight                    
                      font-medium
                      mb-2
                    "
                  >
                    {{ uslugi.usl_name }}
                  </h5>
                  <a
                    :href="route('uslugi')"
                    class="
                      basis-2/12
                      text-end              
                      text-blue-500
                      underline
                      dark:text-blue-500
                      hover:no-underline
                    "
                    >перейти</a
                  >
                  <a
                    :href="route('uslugi.edit', [uslugi.id])"
                    class="
                      basis-2/12
                      text-end              
                      text-blue-500
                      underline
                      dark:text-blue-500
                      hover:no-underline
                    "
                    >редактировать</a
                  >
                  <button 
                    @click="handleDelete(uslugi.id, uslugi.usl_name)"
                    class="btn btn-light w-100 ml-5" 
                    ><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /> </svg>
                  </button>
                  
                </div>
              </div>

            </div>

           <!-- row -->
           <div v-else class="flex justify-center">
            <div class="block p-6 rounded-lg shadow-lg bg-white max-w-sm">
              <h5 class="text-gray-900 text-xl leading-tight font-medium mb-2">
                Услуги не найдены
              </h5>
              <p class="text-gray-700 text-base mb-2">
                Самое время 
                <a :href="route('uslugi.add')"
                class="
                    basis-2/12
                    text-end              
                    text-blue-500
                    underline
                    dark:text-blue-500
                    hover:no-underline
                  "
                  >добавить одну</a>
              </p>
            </div>
          </div>
          <!-- row -->

          <Pagination :links="uslugi.links" />
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
      }
    }
  },

  watch: {
    form: {
      deep: true,
      handler: function (value) {         
          this.$inertia.get('/uslugi/user', { search: this.form.search }, { preserveState: true })
        }
    },
  },

  methods: {
    reset() {
      this.form.search = null
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

