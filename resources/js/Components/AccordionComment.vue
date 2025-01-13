<script setup lang="ts">
import { ref, onMounted } from "vue";
import Answer from "@/Layouts/Answer.vue";

const accordionOpen = ref<boolean>(false);

onMounted(() => {
    accordionOpen.value = false;
});

defineProps({
    article_id: Number,
    question: Number,
    answerid: Number,
    authid: Number,
    type: String,
});
</script>

<template>
    <div class="py-2">
        <div>
            <button
                type="button"
                id="accordion-title-01"
                @click.prevent="accordionOpen = !accordionOpen"
                :aria-expanded="accordionOpen"
                aria-controls="accordion-text-01"
                class="w-full flex text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium"
            >
                <div class="flex-none w-14">
                    <svg
                        class="mr-2 w-3.5 h-3.5"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 20 18"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 5h5M5 8h2m6-3h2m-5 3h6m2-7H2a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h3v5l5-5h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"
                        ></path>
                    </svg>
                </div>
                <div class="flex-none w-14">Ответить</div>
                <div class="grow items-center flex justify-end">
                    <svg
                        class="fill-indigo-500 shrink-0 ml-8"
                        width="16"
                        height="16"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <rect
                            y="7"
                            width="16"
                            height="2"
                            rx="1"
                            class="transform origin-center transition duration-200 ease-out"
                            :class="{ '!rotate-180': accordionOpen }"
                        />
                        <rect
                            y="7"
                            width="16"
                            height="2"
                            rx="1"
                            class="transform origin-center rotate-90 transition duration-200 ease-out"
                            :class="{ '!rotate-180': accordionOpen }"
                        />
                    </svg>
                </div>
            </button>
        </div>
        <div
            id="accordion-text-01"
            role="region"
            aria-labelledby="accordion-title-01"
            class="grid text-sm text-slate-600 overflow-hidden transition-all duration-300 ease-in-out mt-4"
            :class="
                accordionOpen
                    ? 'grid-rows-[1fr] opacity-100'
                    : 'grid-rows-[0fr] opacity-0'
            "
        >
            <div class="overflow-hidden">
                <p class="pb-3">
                    <Answer
                        :question="{id: question}"
                        :answerid="answerid"
                        :answerclass="'py-12 bg-white overflow-hidden shadow-sm sm:rounded-lg'"
                        :authid="authid"
                        :type="type"
                        :article_id="article_id"
                    />
                </p>
            </div>
        </div>
    </div>
</template>
