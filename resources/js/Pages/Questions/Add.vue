<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import RadioLawyer from "@/Components/RadioLawyer.vue";
import SendButton from "@/Components/SendButton.vue";
import SliderQuestions from "@/Layouts/SliderQuestions.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";
import VueWriter from 'vue-writer'

let form = reactive({
  header: "",
  body: "",
});

defineProps({
  lawyers: "Object",
  SliderQ: Array,
});

const buttonDisabled = ref(false);
let arr = ['юристы, которые точно помогут', 'ответят на вопрос бесплатно'];

let submit = () => {
  buttonDisabled.value = true;
  Inertia.post("/questions/post", form);
};

//import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
let title = ref("Задать вопрос юристу");
</script>

<template>
  <Head>
    <title>Задать вопрос юристу онлайн</title>
    <meta name="description" content="Лучшие юристы ответят на Ваш вопрос онлайн бесплатно, без регистрации и смс" />
  </Head>

  <MainHeader />

  <Header :ttl="title" />

  <Body>
    <div class="bg-white md:py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex flex-col items-center">
            <h1 class="text-center mx-5 pb-6 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
              Задать вопрос юристу онлайн
            </h1>

            <div class="flex -space-x-2 overflow-hidden pb-6">

              <img v-for="value in lawyers" :key="value" class="inline-block h-10 w-10 rounded-full ring-2 ring-white"
                :src="'https://nedicom.ru/' + value.avatar_path" width="40" height="40" alt="" />
              <a class="flex items-center justify-center w-10 h-10 text-xs font-medium text-white bg-gray-700 border-2 border-white rounded-full hover:bg-gray-600 dark:border-gray-800"
                href="#">+19</a>
            </div>
            <!-- <svg
                                xmlns="http://www.w3.org/2000/svg"
                                shape-rendering="geometricPrecision"
                                text-rendering="geometricPrecision"
                                image-rendering="optimizeQuality"
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                viewBox="0 0 512 512"
                            >
                                <rect
                                    fill="#10A37F"
                                    width="512"
                                    height="512"
                                    rx="104.187"
                                    ry="105.042"
                                />
                                <path
                                    fill="#fff"
                                    fill-rule="nonzero"
                                    d="M378.68 230.011a71.432 71.432 0 0 0 3.654-22.541 71.383 71.383 0 0 0-9.783-36.064c-12.871-22.404-36.747-36.236-62.587-36.236a72.31 72.31 0 0 0-15.145 1.604 71.362 71.362 0 0 0-53.37-23.991h-.453l-.17.001c-31.297 0-59.052 20.195-68.673 49.967a71.372 71.372 0 0 0-47.709 34.618 72.224 72.224 0 0 0-9.755 36.226 72.204 72.204 0 0 0 18.628 48.395 71.395 71.395 0 0 0-3.655 22.541 71.388 71.388 0 0 0 9.783 36.064 72.187 72.187 0 0 0 77.728 34.631 71.375 71.375 0 0 0 53.374 23.992H271l.184-.001c31.314 0 59.06-20.196 68.681-49.995a71.384 71.384 0 0 0 47.71-34.619 72.107 72.107 0 0 0 9.736-36.194 72.201 72.201 0 0 0-18.628-48.394l-.003-.004zM271.018 380.492h-.074a53.576 53.576 0 0 1-34.287-12.423 44.928 44.928 0 0 0 1.694-.96l57.032-32.943a9.278 9.278 0 0 0 4.688-8.06v-80.459l24.106 13.919a.859.859 0 0 1 .469.661v66.586c-.033 29.604-24.022 53.619-53.628 53.679zm-115.329-49.257a53.563 53.563 0 0 1-7.196-26.798c0-3.069.268-6.146.79-9.17.424.254 1.164.706 1.695 1.011l57.032 32.943a9.289 9.289 0 0 0 9.37-.002l69.63-40.205v27.839l.001.048a.864.864 0 0 1-.345.691l-57.654 33.288a53.791 53.791 0 0 1-26.817 7.17 53.746 53.746 0 0 1-46.506-26.818v.003zm-15.004-124.506a53.5 53.5 0 0 1 27.941-23.534c0 .491-.028 1.361-.028 1.965v65.887l-.001.054a9.27 9.27 0 0 0 4.681 8.053l69.63 40.199-24.105 13.919a.864.864 0 0 1-.813.074l-57.66-33.316a53.746 53.746 0 0 1-26.805-46.5 53.787 53.787 0 0 1 7.163-26.798l-.003-.003zm198.055 46.089-69.63-40.204 24.106-13.914a.863.863 0 0 1 .813-.074l57.659 33.288a53.71 53.71 0 0 1 26.835 46.491c0 22.489-14.033 42.612-35.133 50.379v-67.857c.003-.025.003-.051.003-.076a9.265 9.265 0 0 0-4.653-8.033zm23.993-36.111a81.919 81.919 0 0 0-1.694-1.01l-57.032-32.944a9.31 9.31 0 0 0-4.684-1.266 9.31 9.31 0 0 0-4.684 1.266l-69.631 40.205v-27.839l-.001-.048c0-.272.129-.528.346-.691l57.654-33.26a53.696 53.696 0 0 1 26.816-7.177c29.644 0 53.684 24.04 53.684 53.684a53.91 53.91 0 0 1-.774 9.077v.003zm-150.831 49.618-24.111-13.919a.859.859 0 0 1-.469-.661v-66.587c.013-29.628 24.053-53.648 53.684-53.648a53.719 53.719 0 0 1 34.349 12.426c-.434.237-1.191.655-1.694.96l-57.032 32.943a9.272 9.272 0 0 0-4.687 8.057v.053l-.04 80.376zm13.095-28.233 31.012-17.912 31.012 17.9v35.812l-31.012 17.901-31.012-17.901v-35.8z"
                                />
                            </svg> -->

            <h6 class="text-center mx-5 md:pb-6 font-semibold tracking-tight text-gray-900 dark:text-white">
              <vue-writer :array="arr" :eraseSpeed="20" :typeSpeed="50" :iterations='1'/>
            </h6>

          </div>

          <div class="p-5">
            <form @submit.prevent=" submit ">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-3 w-full">
                  <textarea v-model=" form.header " @input=" onInputheader " maxlength="55" required
                    class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                    id="" rows="2" placeholder="Заголовок или коротко о чем Ваш вопрос"></textarea>
                  <div class="my-1 w-full bg-gray-200 rounded-full h-1 dark:bg-gray-700">
                    <div class="bg-blue-600 h-1 rounded-full" :style="
                      {
                        width: progresswidth + '%',
                                          }
                    "></div>
                  </div>
                  <p class="text-xs text-gray-900 dark:text-white">
                    Символов: {{ wordscounter }}
                  </p>

                  <textarea v-model=" form.body " required
                    class="h-50 form-control mt-3 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                    id="" name="body" rows="8"
                    placeholder="Тут введите описание вопроса. Важно максимально точно задавать вопрос, по статистике успех ответа зависит от детального описания"></textarea>
                </div>

                <div class="mb-3 w-full p-5">
                  <RadioLawyer :lawyers=" lawyers " />
                </div>
              </div>
              <div class="text-center items-center">
                <SendButton class="m-5" id="SendButton" :disabled=" buttonDisabled ">
                  задать вопрос</SendButton>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Body>

  <SliderQuestions :sliderq=" SliderQ " />

  <MainFooter />
</template>

<script>
export default {
  data() {
    return {
      progresswidth: 0,
      wordscounter: 0,
    };
  },
  methods: {
    onInputheader(e) {
      //event, what is e
      (this.header = e.target.value), //this keyword refers to an object, yaheader - method
        (this.wordscounter = this.header.length);
      if (this.header.length <= 50) {
        this.progresswidth = this.header.length * 2;
      }
    },
  },
};
</script>
