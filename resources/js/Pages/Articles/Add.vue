<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import Editor from "@/Components/Tiptap.vue";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";
import { Head } from "@inertiajs/inertia-vue3";

let form = reactive({
  header: "",
  description: "",
  body: "",
});

defineProps({
  auth: Object,
});

let submit = () => {
  Inertia.post("/articles/create", form);
};

let title = ref("Добавить статью");
</script>

<template>
  <Head>
    <title>{{title}}</title>
    <meta name="description" content="Страница статьи" />
  </Head>

  <MainHeader :auth="auth" />

  <Header/>

  <Body>
    <div class="bg-white py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 p-5">
            <div class="">
              <form @submit.prevent="submit" class="">
                <textarea
                  v-model="form.header"
                  @input="onInputheader"
                  spellcheck="true"
                  maxlength="55"
                  class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                  required
                  id=""
                  rows="2"
                  placeholder="Тут Ваш прекрасный Заголовок. Задумайтесь, что в нем зацепит внимание пользователя"
                ></textarea>
                <div
                  class="my-1 w-full bg-gray-200 rounded-full h-1 dark:bg-gray-700"
                >
                  <div
                    class="bg-blue-600 h-1 rounded-full"
                    :style="{ width: progresswidth + '%' }"
                  ></div>
                </div>
                <p class="text-xs text-gray-900 dark:text-white">
                  Символов: {{ wordscounter }}
                </p>
                <textarea
                  v-model="form.description"
                  @input="onInputdesc"
                  spellcheck="true"
                  class="h-20 form-control mt-3 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                  id=""
                  required
                  name="description"
                  rows="3"
                  placeholder="Здесь Ваше интересное описание, которое расскажет о чем статья. Его отображают поисковики, поэтому не забудьте парочку ключевых слов."
                ></textarea>

                <editor v-model="form.body" />

                <button
                  v-if="form.body"
                  type="submit"
                  class="my-5 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"
                >
                  Опубликовать
                </button>
              </form>
            </div>

            <div class="hidden md:contents">
              <div class="bg-gray-50 text-gray-800">
                <h3 class="text-2xl font-bold p-3">Яндекс</h3>
                <div class="rounded-lg shadow-lg bg-white p-5 mx-5">
                  <h5
                    class="text-blue-800 text-xl line-clamp-2 leading-tight font-medium hover:text-red-400"
                  >
                    {{ yaheader }}
                  </h5>
                  <a
                    href="#!"
                    class="text-green-700 hover:text-red-400 transition duration-300 ease-in-out mb-4"
                    >https://nedicom.ru/статьи/<span>{{ url }}</span></a
                  >
                  <p class="text-gray-700 line-clamp-3 text-base text-ellipsis">
                    {{ yabody }}
                  </p>
                </div>
                <h3 class="text-2xl font-bold p-3 mt-5">Google</h3>
                <div class="rounded-lg shadow-lg bg-white p-5 mx-5">
                  <a href="#!" class="transition duration-300 ease-in-out"
                    >https://nedicom.ru/статьи/<span>{{ url }}</span></a
                  >
                  <h5
                    class="text-blue-700 no-underline line-clamp-2 hover:underline text-xl leading-tight font-medium my-1"
                  >
                    {{ gooheader }}
                  </h5>
                  <p
                    class="text-gray-700 text-base line-clamp-3 text-ellipsis overflow-hidden ..."
                  >
                    {{ goobody }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Body>

  <MainFooter />
</template>

<script>
/* autosave
setInterval(function () {
  alert("form.body");
}, 60 * 1000);*/

export default {
  //?export object to DOM??
  data() {
    return {
      //retirning value or what?
      yaheader: "Заголовок идеальной статьи в Яндексе. До 55 символов", //yaheader is variable for ???
      yabody:
        "Описание идеальной статьи в Яндексе. Яндекс любит краткое описание до 160 символов на компьютере, и до 80 символов на экране мобильного.",
      gooheader: "Заголовок идеальной статьи в Google",
      goobody:
        "Описание идеальной статьи в Google. Google тоже предпочитает до 160 символов на десктопе и до 80 на мобильном. ",
      progresswidth: 0,
      wordscounter: 0,
      titletext: "sometest",
      url: "ссылка на статью",
    };
  },
  methods: {
    onInputheader(e) {
      //event, what is e
      (this.yaheader = this.gooheader = e.target.value), //this keyword refers to an object, yaheader - method
        (this.wordscounter = this.yaheader.length);
      if (this.yaheader.length <= 50) {
        this.progresswidth = this.yaheader.length * 2;
      }
      if (e.target.value.length < 20) {
        this.url = e.target.value;
      }
    },

    onInputdesc(e) {
      (this.yabody = e.target.value), (this.goobody = e.target.value);
    },
  },
};
</script>
    