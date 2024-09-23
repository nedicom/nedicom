<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import AccordionComment from "@/Components/AccordionComment.vue";


defineProps({
    answers: "Object",
    question: "Number",
    authid: "Number",
});

</script>

<template>    
    <div class="grid grid-cols-1 gap-9 divide-y">
        <!-- comment -->
        <div v-for="answer in answers" :key="answer.id">
            <div
            :id="answer.id"
                class="min-w-full p-6 bg-white max-w-sm flex flex-col" itemprop="suggestedAnswer" itemscope itemtype="https://schema.org/Answer"
            >
                <div class="flex flex-right mb-2">
                    <Link
                        :href="route('lawyer', answer.user_ans.id)"
                        class="hover:underline flex flex-justify"
                        itemprop="author" itemscope itemtype="https://schema.org/Person"
                    >
                        <img
                            :src="'https://nedicom.ru/' + answer.user_ans.avatar_path"
                            width="40"
                            class="rounded-full mr-3"
                        />
                        <p
                            class="mr-3 text-sm text-gray-900 dark:text-white font-semibold h-min-24 flex items-center" itemprop="name"
                        >
                            {{answer.user_ans.name}}
                        </p>
                    </Link>
                    <p
                            class="text-gray-400 text-sm mx-4 flex items-center"
                        >
                            {{ answer.created_at }}
                        </p>
                </div>
                <p class="text-gray-700 text-base h-min-24 mb-2" itemprop="text">
                    {{ answer.body }}
                </p>

                <AccordionComment :question="question" :answerid="answer.id" :authid="authid"/>
            </div>

            <!--subcomments-->
            <div v-if="answer.subcomments">
                <div v-for="subcomments in answer.subcomments" :key="subcomments.id" class="ml-16">
                <div
                    class="min-w-full p-6 bg-white max-w-sm flex flex-col"
                >
                    <div class="flex flex-right mb-2">
                        <Link
                            :href="route('lawyer', subcomments.id)"
                            class="hover:underline flex flex-justify"
                        >
                            <img
                                :src=" subcomments.avatar_path"
                                width="40"
                                class="rounded-full"
                            />
                            <p
                                class="mr-3 text-sm text-gray-900 dark:text-white font-semibold h-min-24 flex items-center"
                            >
                            {{subcomments.name}}
                            </p>
                        </Link>
                        <p
                                class="text-gray-400 text-sm mx-4 flex items-center"
                            >
                            {{subcomments.pivot.created_at}}
                            </p>
                    </div>
                    <p class="text-gray-700 text-base h-min-24 mb-2">
                        {{ subcomments.pivot.body }}
                    </p>

                    <AccordionComment :question="question" :answerid="answer.id" :authid="authid"/>
                </div>
            </div>

            </div>
        </div>
        <!-- comment -->
    </div>
</template>
