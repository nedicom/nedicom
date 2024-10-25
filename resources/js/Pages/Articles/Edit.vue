<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Editor from '@/Components/Tiptap.vue';
import Practicecropper from '@/Components/Practicecropper.vue';
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";

let set = defineProps({
  article: Object,
  uslugi: Object,
  auth: Object,
});

let form = reactive({
  header: set.article.header,
  description: set.article.description,
  body: set.article.body,
  usluga_id: set.article.usluga_id,
  id: set.article.id,
  youtube: set.article.youtube_file_path,
  auth: Object,
});


function submit() {
  if (set.article.id) {
    Inertia.post("/articles/{url}/update", form);
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

                <input v-model="form.id" class="invisible">


                <textarea v-model="form.header" @input="onInputheader" spellcheck="true" name="header" maxlength="55"
                  class="
                      form-control
                      block
                      w-full
                      px-3
                      py-1.5
                      text-base
                      font-normal
                      text-gray-700
                      bg-white bg-clip-padding
                      border border-solid border-gray-300
                      rounded
                      transition
                      ease-in-out
                      m-0
                      focus:text-gray-700
                      focus:bg-white
                      focus:border-blue-600
                      focus:outline-none
                    " rows="2" required></textarea>
                <div class="
                      my-1
                      w-full
                      bg-gray-200
                      rounded-full
                      h-1
                      dark:bg-gray-700
                    ">
                  <div class="h-1 rounded-full" :class="headProgressColor" :style="{ width: progresswidth + '%' }">
                  </div>
                </div>
                <p class="text-xs text-gray-900 dark:text-white">
                  Символов: {{ wordscounter }} Макс: 55 Рекомендовано: до 55
                </p>
                <textarea v-model="form.description" @input="onInputdesc" maxlength="200" spellcheck="true" class="
                      h-28
                      form-control
                      mt-3
                      block
                      w-full
                      px-3
                      py-1.5
                      text-base
                      font-normal
                      text-gray-700
                      bg-white bg-clip-padding
                      border border-solid border-gray-300
                      rounded
                      transition
                      ease-in-out
                      m-0
                      focus:text-gray-700
                      focus:bg-white
                      focus:border-blue-600
                      focus:outline-none
                    " required></textarea>
                <div class="
                      my-1
                      w-full
                      bg-gray-200
                      rounded-full
                      h-1
                      dark:bg-gray-700
                    ">
                  <div class="h-1 rounded-full" :class="progressColor" :style="{ width: progressdescwidth + '%' }">
                  </div>
                </div>
                <p class="text-xs text-gray-900 dark:text-white">
                  Символов: {{ wordsdesccounter }} Макс: 260 Рекомендовано: от 80 до 160
                </p>

                <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">Выберите
                  категорию</label>
                <select v-model="form.usluga_id"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option disabled value="">Выберите один из вариантов</option>
                  <option v-for="option in uslugi" :key="option.id" v-bind:value="option.id">
                    {{ option.usl_name }}
                  </option>
                </select>

                <label class="block mt-5 mb-2 text-sm font-medium text-gray-900 dark:text-white">Ссылка на
                  youtube</label>
                <input v-model="form.youtube" class="
                      form-control
                      mt-3
                      block
                      w-full
                      px-3
                      py-1.5
                      text-base
                      font-normal
                      text-gray-700
                      bg-white bg-clip-padding
                      border border-solid border-gray-300
                      rounded
                      transition
                      ease-in-out
                      m-0
                      focus:text-gray-700
                      focus:bg-white
                      focus:border-blue-600
                      focus:outline-none
                    ">
                <p class="mt-2 text-sm dark:text-green-500"><span class="font-medium">В ютуб нажмите "поделиться", потом
                    "встроить".</span> Потом скопируйте код между кавычками из src="код который скопировать". Сложно, но
                  только так. </p>

              </div>

              <div class="hidden md:contents">
                <div class="bg-gray-50 text-gray-800">
                  <h3 class="text-2xl font-bold p-3">Яндекс</h3>
                  <div class="rounded-lg shadow-lg bg-white p-5 mx-5">
                    <h5 class="
                        text-blue-800 text-xl
                        line-clamp-2
                        leading-tight
                        font-medium
                        hover:text-red-400
                      ">
                      {{ yaheader }}
                    </h5>
                    <a href="#!" class="
                        text-green-700
                        hover:text-red-400
                        transition
                        duration-300
                        ease-in-out
                        mb-4
                      ">https://nedicom.ru/<span>{{ url }}</span></a>
                    <p class="text-gray-700 line-clamp-3 text-base text-ellipsis">
                      {{ yabody }}
                    </p>
                  </div>
                  <h3 class="text-2xl font-bold p-3 mt-5">Google</h3>
                  <div class="rounded-lg shadow-lg bg-white p-5 mx-5">
                    <a href="#!" class="transition duration-300 ease-in-out">https://nedicom.ru/<span>{{ url
                        }}</span></a>
                    <h5 class="
                        text-blue-700
                        no-underline
                        line-clamp-2
                        hover:underline
                        text-xl
                        leading-tight
                        font-medium
                        my-1
                      ">
                      {{ gooheader }}
                    </h5>
                    <p class="
                        text-gray-700 text-base
                        line-clamp-3
                        text-ellipsis
                        overflow-hidden                      
                      ">
                      {{ goobody }}
                    </p>
                  </div>
                </div>
              </div>


            </div>


            <editor spellcheck="true" v-model="form.body" />
            <div class="flex justify-center">
              <button type="submit" class="
                        my-5
                        inline-flex
                        items-center
                        px-5
                        py-2.5
                        text-sm
                        font-medium
                        text-center text-white
                        bg-blue-700
                        rounded-lg
                        focus:ring-4 focus:ring-blue-200
                        dark:focus:ring-blue-900
                        hover:bg-blue-800
                        text-center
                      ">
                Обновить
              </button>
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
      yaheader: "Заголовок идеальной статьи в Яндексе. До 55 символов", //yaheader is variable
      yabody:
        "Описание идеальной статьи в Яндексе. Яндекс любит краткое описание до 160 символов на компьютере, и до 80 символов наэкране мобильного.",
      gooheader: "Заголовок идеальной статьи в Google",
      goobody:
        "Описание идеальной статьи в Google. Google тоже предпочитает до 160 символов на десктопе и до 80 на мобильном. ",
      progresswidth: 0,
      wordscounter: 0,
      progressdescwidth: 0,
      wordsdesccounter: 0,
      titletext: "sometest",
      headProgressColor: '',
      progressColor: '',
      url: 'адрес статьи',
    };
  },
  methods: {
    onInputheader(e) {
      (this.yaheader = this.gooheader = e.target.value), //this keyword refers to an object, onInputheader - method
        (this.wordscounter = this.yaheader.length);
      if (this.yaheader.length <= 55) {
        this.progresswidth = 100 / 55 * this.yaheader.length;
      }

      if (e.target.value.length < 20) {
        this.url = e.target.value;
      }

      if (this.yaheader.length > 0) {
        this.headProgressColor = 'bg-gray-300';
      }
      if (this.yaheader.length > 40) {
        this.headProgressColor = 'bg-blue-600';
      }
    },
    onInputdesc(e) {
      (this.yabody = e.target.value), (this.goobody = e.target.value);
      (this.wordsdesccounter = this.yabody.length);
      if (this.yabody.length <= 260) {
        this.progressdescwidth = 100 / 260 * this.yabody.length;
      }
      if (this.yabody.length > 0) {
        this.progressColor = 'bg-gray-300';
      }
      if (this.yabody.length > 60) {
        this.progressColor = 'bg-blue-600';
      }
      if (this.yabody.length > 160) {
        this.progressColor = 'bg-red-50 0';
      }
    },
  },
};
</script>