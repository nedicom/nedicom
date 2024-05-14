<script setup>
import { ref } from "vue";

let props = defineProps({
    popular_question: Array,
});

let items = ref(props.popular_question);

/*
if(props.popular_question == null){    
    items.value = [{ 'question':'test', 'answer':'test'}];
}
else{
    items.value = props.popular_question;
}*/

function buttonClickItems() {
    items.value.push({ question: '', answer: '' });
}

function buttonClickUnpushItems() {
    items.value.splice(-1);
}
</script>

<template>
    <div class="my-12 px-2 shadow-md">
        <p class="font-semibold mt-5">Ответы на частозадаваемые вопросы</p>
        <div v-for="item in items" class="col-span-3 mb-5">

            <label for="otzivbody" class="block text-sm font-medium leading-6 text-gray-900">вопрос</label>
            
            <textarea v-model="item.question"  spellcheck="true" name="" maxlength="155" class="
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
            value=item.message
            
        " placeholder="Напишите вопрос" rows="1"></textarea>

            <label for="otzivbody" class="block text-sm font-medium leading-6 text-gray-900">ответ</label>
            <textarea v-model="item.answer" spellcheck="true" name="" maxlength="555" class="
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
        " placeholder="Дайте ответ. Не оставляйте поле пустым" rows="2"></textarea>

        </div>

        <div class="flex items-center justify-evenly ">
            <div :onClick="buttonClickItems" class="
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
                  ">
                добавить вопрос
            </div>

            <div :onClick="buttonClickUnpushItems" class="
                    my-5
                    inline-flex
                    items-center
                    px-5
                    py-2.5
                    text-sm
                    font-medium
                    text-center text-white
                    bg-red-700
                    rounded-lg
                    focus:ring-4 focus:ring-blue-200
                    dark:focus:ring-blue-900
                    hover:bg-blue-800
                  ">
                удалить вопрос
            </div>
        </div>

    </div>
</template>