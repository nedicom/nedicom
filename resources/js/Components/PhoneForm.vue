<script setup>
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";

let set = defineProps({
  modalPageTitle: String,
})

let form = reactive({
  one: "",
  two: "",
  three: "",
  four: "",
  five: "",
  six: "",
  seven: "",
  eight: "",
  nine: "",
  ten: "",
  url: set.modalPageTitle,
});

let submit = () => {
  Inertia.post("/phone/send", form);
};
</script>


<template>
  <!--phone form-->
  <form @submit.prevent="submit">
    <label class="mx-5 my-2">
      <div class="text-center"><p class="goo text-xl font-semibold bg-white rounded-lg py-1 px-4 md:opacity-100 opacity-100">Заказать обратный звонок</p></div>      
    
    <div class="flex justify-center bg-white py-2 px-1 rounded-lg mt-5 space-x-0.5 md:space-x-1">
          <input type="text" value="+7" disabled
            class="block w-5 h-6 px-0 md:w-7 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border-none" />
          
            <input type="text" value="(" disabled
            class="block w-2 h-6 px-0 md:w-2 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border-none" />
          
            <input type="text" maxlength="1" name="one" id="one" v-model="form.one"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-next="two"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg"
            required />

          <input type="text" maxlength="1" name="two" id="two" v-model="form.two"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-next="three" data-focus-input-prev="one"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            required />


          <input type="text" maxlength="1" name="three" id="three" v-model="form.three"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-next="four" data-focus-input-prev="two"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            required />

          <input type="text" value=")" disabled
            class="block w-2 h-6 px-0 md:w-2 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border-none" />


          <input type="text" maxlength="1" name="four" id="four" v-model="form.four"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-next="five" data-focus-input-prev="three"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            required />

          <input type="text" maxlength="1" name="five" id="five" v-model="form.five"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-next="six" data-focus-input-prev="four"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            required />

          <input type="text" maxlength="1" name="six" id="six" v-model="form.six"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-next="seven" data-focus-input-prev="five"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl py-1 mx-0 font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            required />

          <input type="text" maxlength="1" name="seven" id="seven" v-model="form.seven"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-next="eight" data-focus-input-prev="six"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            required />

          <input type="text" maxlength="1" name="eight" id="eight" v-model="form.eight"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-next="nine" data-focus-input-prev="seven"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            aria-label="Search" required />

          <input type="text" maxlength="1" name="nine" id="nine" v-model="form.nine"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-next="ten" data-focus-input-prev="eight"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            required />

          <input type="text" maxlength="1" name="ten" id="ten" v-model="form.ten"
            onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-focus-input-init
            data-focus-input-prev="nine"
            class="block w-6 h-6 px-0 md:w-10 md:h-10 md:text-2xl font-extrabold text-center text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            required />

      </div>
  </label>

  <div>
    <div class="flex justify-between">
      <div class="flex items-start">
        <div class="flex items-center h-5">
          <input id="remember" type="checkbox" value="" checked
            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
            required />
        </div>
        <label for="remember"
          class="ml-2 text-sm font-medium bg-white rounded-lg px-1 text-gray-900 dark:text-gray-300">Согласен с
          политикой сайта</label>
      </div>
      <a href="/policy"
        class="text-sm text-blue-700 bg-white rounded-lg px-1 hover:underline dark:text-blue-500">Подробнее</a>
    </div>
    <button type="submit"
      class="w-full px-5 py-2.5 my-2 text-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">
      Отправить
    </button>
  </div>
  </form>
  <!--phone form-->
</template>

<script>

export default {
  mounted() {
    function focusNextInput(el, prevId, nextId) {
      if (el.value.length === 0) {
        if (prevId) {
          let prev = document.getElementById(prevId);
          prev.focus();
          prev.select();
        }
      } else {
        if (nextId) {
          let next = document.getElementById(nextId);
          next.focus();
          next.select();
        }
      }
    }

    document.querySelectorAll('[data-focus-input-init]').forEach(function (element) {
      element.addEventListener('keyup', function () {
        const prevId = this.getAttribute('data-focus-input-prev');
        const nextId = this.getAttribute('data-focus-input-next');
        focusNextInput(this, prevId, nextId);
      });
      element.addEventListener('click', function () {
        this.select();
      });
    });
  }
}

</script>
