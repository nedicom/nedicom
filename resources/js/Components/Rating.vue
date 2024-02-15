<script setup>
import { ref, reactive } from "vue";

defineProps({
    modelValue: String,
})

const emit = defineEmits(['update:modelValue'])

const stars = [
    { id: 'one', value: 1, class: 'one two three four five' },
    { id: 'two', value: 2, class: 'two three four five' },
    { id: 'three', value: 3, class: 'three four five' },
    { id: 'four', value: 4, class: 'four five' },
    { id: 'five', value: 5, class: 'five' }]

const isHovered = ref(false)

const classObject = reactive({
    'w-4': true,
    ' h-4': true,
    'ms-1': true,
    'cursor-pointer': true,
    'text-gray-700': isHovered,
})

let stop = true;

let emitValue = function (x) {    
    stop = false;
    for (let i = 1; i <= 5; i++) {

        if (i <= x) {
            document.getElementsByName(i)[0].style.color = 'red';
            document.getElementsByName(i)[0].style.opacity = '1';
        }
        else {
            document.getElementsByName(i)[0].style.color = 'grey';
            document.getElementsByName(i)[0].style.opacity = '0.2';
        }
    }

    emit('update:modelValue', x);
}

let hoverFunction = function (x) {
    for (let i = 1; i <= 5; i++) {
        if (i <= x) {
            document.getElementsByName(i)[0].style.color = 'red';
        }
        else {
            document.getElementsByName(i)[0].style.color = 'grey';
        }
    }
}

let leaveFunction = function (x) {
    if (stop === true) {
        for (let i = 1; i <= 5; i++) {
            document.getElementsByName(i)[0].style.color = 'gray';
        }
    }
}
</script>

<template>
    <div class="flex items-center justify-center">
        <span v-for="item in stars">
            <label :for="item.id">

                <input type="radio" :value="modelValue" @click="emitValue(item.value)" :name="item.id" :id="item.id" class="hidden">

                <svg  @mouseover="hoverFunction(item.value)"
                    @mouseleave="leaveFunction(item.value)" :class="[classObject, item.class]" :name="item.value"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                </svg>
            </label>

        </span>

    </div>

</template>
