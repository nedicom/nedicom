<script setup>
import MainHeader from "@/Layouts/MainHeader.vue";
import Header from "@/Layouts/Header.vue";
import Body from "@/Layouts/Body.vue";
import MainFooter from "@/Layouts/MainFooter.vue";
import { ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";

let title = ref("Юрист по наследству");

let form = reactive({
  phone: "",
});

let submit = () => {
  Inertia.post("/phone/send", form);
}
</script>

<template>
    <Head>
      <title>Юрист по наследству</title>
    </Head>
  
    
    <MainHeader />

    <Header :ttl="title" />

    <Body>

      <!-- main banner -->
        <div
          class="relative overflow-hidden bg-cover bg-no-repeat p-12 text-center mb-9"
          style="background-image: url('/storage/images/landing/nasledstvo/mainpc.jpg'); height: 600px">
          <div
            class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-fixed"
            style="background-color: rgba(0, 0, 0, 0.6)">

            <div class="h-full md:w-1/2 md:float-right">
              <div class="flex h-full items-center justify-center">            
                <div class="text-white">
                  <h2 class="mb-4 text-4xl font-semibold">Адвокат</h2>
                  <h4 class="mb-12 text-2xl font-semibold">Денисова <br>Екатерина Александровна</h4>
                  <h2 class="mt-12 text-3xl font-semibold">Запись</h2>
                  <h4 class="text-xl font-semibold">на консультацию</h4>
                  <div class="">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">запись на консультацию</label>
                            <input v-model="form.phone" type="number" name="phone" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="+7" required>
                        </div>
                        <div class="flex justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required>
                                </div>
                                <label for="remember" class="ml-2 text-sm font-medium text-white-900 dark:text-gray-300">Согласен с политикой сайта</label>
                            </div>
                            <a href="/policy" class="text-sm text-blue-700 hover:underline dark:text-blue-500">Подробнее</a>
                        </div>
                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Отправить</button>
                    </form>
                </div>

                </div>
              </div>
            </div>            
          </div>         
        </div>
      <!-- main banner -->

       <!-- case card -->   
      <h1 class="text-4xl font-semibold text-grey text-center py-10">
        Берусь за дело, только если его можно выиграть
      </h1>

      <div class="max-w-6xl mx-auto md:my-9 sm:px-6 lg:px-8 text-center">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-9">
            <!-- card -->
            <div class="flex justify-center m-3 md:m-0">
              <div class="block min-w-full p-6 rounded-lg shadow-lg bg-white max-w-sm">
                <h5 class="text-gray-900 text-xl leading-tight line-clamp-1 font-medium mb-2">
                  Оспаривание наследства
                </h5>
                <p class="text-gray-700 text-base line-clamp-3 h-min-24 mb-2">
                  Оспаривание завещаний, дарственных, других договоров по различным мотивам
                </p>
              
              </div>
            </div>
            <!-- card -->

            <!-- card -->
            <div class="flex justify-center m-3 md:m-0">
              <div class="block min-w-full p-6 rounded-lg shadow-lg bg-white max-w-sm">
                <h5 class="text-gray-900 text-xl leading-tight line-clamp-1 font-medium mb-2">
                  Оформление недвижимости
                </h5>
                <p class="text-gray-700 text-base line-clamp-3 h-min-24 mb-2">
                  Если умерший не успел оформить самострой, потерял документы или не оформил права при жизни
                </p>
          
              </div>
            </div>
            <!-- card -->

            <!-- card -->
            <div class="flex justify-center m-3 md:m-0">
              <div class="block min-w-full p-6 rounded-lg shadow-lg bg-white max-w-sm">
                <h5 class="text-gray-900 text-xl leading-tight line-clamp-1 font-medium mb-2">
                  Установление родства
                </h5>
                <p class="text-gray-700 text-base line-clamp-3 h-min-24 mb-2">
                  Установление факта родственных отношений для оформления наследства
                </p>
   
              </div>
            </div>
            <!-- card -->

            <!-- card -->
            <div class="flex justify-center m-3 md:m-0">
              <div class="block min-w-full p-6 rounded-lg shadow-lg bg-white max-w-sm">
                <h5 class="text-gray-900 text-xl leading-tight line-clamp-1 font-medium mb-2">
                  Оформление отношений
                </h5>
                <p class="text-gray-700 text-base line-clamp-3 h-min-24 mb-2">
                  Рекомендации и документы для гарантий при оформлении имущества еще при жизни 
                </p>
   
              </div>
            </div>
            <!-- card -->

            <!-- card -->
            <div class="flex justify-center m-3 md:m-0">
              <div class="block min-w-full p-6 rounded-lg shadow-lg bg-white max-w-sm">
                <h5 class="text-gray-900 text-xl leading-tight line-clamp-1 font-medium mb-2">
                  Ведение наследства
                </h5>
                <p class="text-gray-700 text-base line-clamp-3 h-min-24 mb-2">
                  Сопровождение оформления у нотариуса, если у Вы находитесь далеко или у Вас нет времени
                </p>
         
              </div>
            </div>
            <!-- card -->

            <!-- card -->
            <div class="flex justify-center m-3 md:m-0">
              <div class="block min-w-full p-6 rounded-lg shadow-lg bg-white max-w-sm">
                <h5 class="text-gray-900 text-xl leading-tight line-clamp-1 font-medium mb-2">
                  Иные случаи
                </h5>
                <p class="text-gray-700 text-base line-clamp-3 h-min-24 mb-2">
                  Экзотические случаи вроде оформлени акций, соц. найма, криптовалюты и прочее
                </p>
              
              </div>
            </div>
            <!-- card -->
        </div>
      </div>
       <!-- case card -->  


          <!-- secondary banner - testimonials -->
            <h1 class="text-4xl font-semibold text-grey text-center py-10">
              В работе адвоката главное - репутация
            </h1>
            <div
              class="relative overflow-hidden bg-cover bg-no-repeat my-9 p-12 text-center h-96"
              style="background-image: url('/storage/images/landing/nasledstvo/testimonials.jpg');">
              <div
                class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-fixed"
                style="background-color: rgba(0, 0, 0, 0.6)">
                  <div class="flex h-full items-center flex justify-around grid grid-cols-1 md:grid-cols-2 gap-9">            
                      <h2 class="m-4 text-4xl font-semibold text-white">Яндекс карты <p class="mt-4">4.6</p></h2>
                      <h2 class="m-4 text-4xl font-semibold text-white">Авито <p class="mt-4"> 5.0</p></h2>                        

                    </div>
              </div>            
            </div> 
          <!-- secondary banner - testimonials -->



          <!-- practice -->   

          <h1 class="my-6 py-6 text-4xl font-semibold text-grey text-center">Делюсь успехами с Вами</h1>


<div id="custom-controls-gallery" class="relative w-full" data-carousel="slide">
    <!-- Carousel wrapper -->
    <div class="relative h-96 overflow-hidden rounded-lg md:h-screen">
         <!-- Item 1 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="/storage/images/landing/nasledstvo/court1.jpg" class="absolute block max-w-full h-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="">
        </div>
        <!-- Item 2 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
            <img src="/storage/images/landing/nasledstvo/court1.jpg" class="absolute block max-w-full h-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="">
        </div>
        <!-- Item 3 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="/storage/images/landing/nasledstvo/court1.jpg" class="absolute block max-w-full h-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="">
        </div>
        <!-- Item 4 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="/storage/images/landing/nasledstvo/court1.jpg" class="absolute block max-w-full h-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="">
        </div>
        <!-- Item 5 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="/storage/images/landing/nasledstvo/court1.jpg" class="absolute block max-w-full h-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="">
        </div>
    </div>
    <div class="flex justify-center items-center pt-4">
        <button type="button" class="flex justify-center items-center mr-4 h-full cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="text-gray-400 hover:text-gray-900 dark:hover:text-white group-focus:text-gray-900 dark:group-focus:text-white">
                <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Вперед</span>
            </span>
        </button>
        <button type="button" class="flex justify-center items-center h-full cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="text-gray-400 hover:text-gray-900 dark:hover:text-white group-focus:text-gray-900 dark:group-focus:text-white">
                <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Назад</span>
            </span>
        </button>
    </div>
</div>
<!-- practice -->   

          <!-- address -->
          <h2 class="my-6 py-6 text-3xl font-semibold text-grey text-center">+7 978 8838 978</h2>
          <div class="flex items-center justify-center"><p><svg class="svg-icon block h-9 w-auto fill-current text-gray-800" viewBox="0 0 20 20"><path d="M17.659,3.681H8.468c-0.211,0-0.383,0.172-0.383,0.383v2.681H2.341c-0.21,0-0.383,0.172-0.383,0.383v6.126c0,0.211,0.172,0.383,0.383,0.383h1.532v2.298c0,0.566,0.554,0.368,0.653,0.27l2.569-2.567h4.437c0.21,0,0.383-0.172,0.383-0.383v-2.681h1.013l2.546,2.567c0.242,0.249,0.652,0.065,0.652-0.27v-2.298h1.533c0.211,0,0.383-0.172,0.383-0.382V4.063C18.042,3.853,17.87,3.681,17.659,3.681 M11.148,12.87H6.937c-0.102,0-0.199,0.04-0.27,0.113l-2.028,2.025v-1.756c0-0.211-0.172-0.383-0.383-0.383H2.724V7.51h5.361v2.68c0,0.21,0.172,0.382,0.383,0.382h2.68V12.87z M17.276,9.807h-1.533c-0.211,0-0.383,0.172-0.383,0.383v1.755L13.356,9.92c-0.07-0.073-0.169-0.113-0.27-0.113H8.851v-5.36h8.425V9.807z"></path></svg></p></div>
          <h5 class="my-6 py-6 text-2xl font-semibold text-grey text-center">Симферополь, улица Долгоруковская, 5</h5>
          <div class="flex items-center justify-center"><p><svg class="svg-icon block h-9 w-auto fill-current text-gray-800" viewBox="0 0 20 20"><path d="M17.659,3.681H8.468c-0.211,0-0.383,0.172-0.383,0.383v2.681H2.341c-0.21,0-0.383,0.172-0.383,0.383v6.126c0,0.211,0.172,0.383,0.383,0.383h1.532v2.298c0,0.566,0.554,0.368,0.653,0.27l2.569-2.567h4.437c0.21,0,0.383-0.172,0.383-0.383v-2.681h1.013l2.546,2.567c0.242,0.249,0.652,0.065,0.652-0.27v-2.298h1.533c0.211,0,0.383-0.172,0.383-0.382V4.063C18.042,3.853,17.87,3.681,17.659,3.681 M11.148,12.87H6.937c-0.102,0-0.199,0.04-0.27,0.113l-2.028,2.025v-1.756c0-0.211-0.172-0.383-0.383-0.383H2.724V7.51h5.361v2.68c0,0.21,0.172,0.382,0.383,0.382h2.68V12.87z M17.276,9.807h-1.533c-0.211,0-0.383,0.172-0.383,0.383v1.755L13.356,9.92c-0.07-0.073-0.169-0.113-0.27-0.113H8.851v-5.36h8.425V9.807z"></path></svg></p></div>
          <h5 class="my-6 py-6 text-2xl font-semibold text-grey text-center">Консультирование ведется только по предварительной записи</h5>
          <!-- address --> 

    </Body>
  

  
  <MainFooter />
  
</template>