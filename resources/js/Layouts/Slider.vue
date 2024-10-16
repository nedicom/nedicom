<script setup>
defineProps({
    sliderheader: String,
    practice: Array,
});
</script>

<template>
    <div class="mb-12 md:my-12">
        <h3 class="text-4xl mx-12 font-semibold text-grey text-center py-10">
            {{ sliderheader }}
        </h3>

        <div v-if="practice">
            <Carousel
                v-bind="settings"
                :breakpoints="breakpoints"
                :wrapAround="true"
                :transition="500"
            >
                <Slide v-for="slide in practice" :key="slide.practice_file_path">
                    <div
                        class="carousel__item w-full flex items-center justify-center h-128 bg-cover"
                        :style="{
                            backgroundImage:
                                'url(https://nedicom.ru/' +
                                slide.practice_file_path +
                                ')',
                        }"
                    >
                        <div
                            v-if="slide.header"
                            class="bg-white rounded-lg py-1 px-4 mx-6 grid grid-cols-1 content-center"
                        >
                            <div class="line-clamp-5 max-w-xl">
                                <p class="my-4 text-sm">
                                    {{ slide.description }}
                                </p>
                            </div>
                            <div>
                                <a
                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-700"
                                    target="_blank"
                                    :href="'/articles/' + slide.url"
                                    >Подробнее
                                    <span>→</span></a
                                >
                            </div>
                        </div>
                    </div>
                </Slide>

                <template #addons>
                    <Navigation />
                    <Pagination />
                </template>
            </Carousel>
        </div>

        <div v-else>
            <Carousel :itemsToShow="3.95" :wrapAround="true" :transition="500">
                <Slide v-for="slide in 1" :key="slide">
                    <div class="carousel__item">
                        Юрист еще не добавил практику
                    </div>
                </Slide>

                <template #addons>
                    <Navigation />
                    <Pagination />
                </template>
            </Carousel>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { Carousel, Pagination, Navigation, Slide } from "vue3-carousel";

import "vue3-carousel/dist/carousel.css";

export default defineComponent({
    name: "Breakpoints",
    components: {
        Carousel,
        Slide,
        Navigation,
        Pagination,
    },
    data: () => ({
        // carousel settings
        settings: {
            itemsToShow: 1,
            snapAlign: "center",
        },
        // breakpoints are mobile first
        // any settings not specified will fallback to the carousel settings
        breakpoints: {
            // 700px and up
            1024: {
                itemsToShow: 3.95,
                snapAlign: "center",
            },
        },
    }),
});
</script>

<style scoped>
.carousel__slide {
    padding: 5px;
}

.carousel__viewport {
    perspective: 2000px;
}

.carousel__track {
    transform-style: preserve-3d;
}

.carousel__slide--sliding {
    transition: 0.5s;
}

.carousel__slide {
    opacity: 0.9;
    transform: rotateY(-20deg) scale(0.9);
}

.carousel__slide--active ~ .carousel__slide {
    transform: rotateY(20deg) scale(0.9);
}

.carousel__slide--prev {
    opacity: 1;
    transform: rotateY(-10deg) scale(0.95);
}

.carousel__slide--next {
    opacity: 1;
    transform: rotateY(10deg) scale(0.95);
}

.carousel__slide--active {
    opacity: 1;
    transform: rotateY(0) scale(1.2);
}
</style>
