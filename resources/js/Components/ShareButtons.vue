<script setup>
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";

const set = defineProps({
  bundle: Object,
  auth: Object,
});

let iconCopy = ref(true);
let href;
let tgHref;
let vkHref;
let okHref;
let invisbookmrks = ref(true);
let invislike = ref(true);
let invisshare = ref(true);

let submit = (property, id, type, value) => {
  if (set.auth) {
    Inertia.post(
      "/social",
      { property: property, id: id, type: type, value: value },
      { preserveScroll: true }
    );
  }
};

function setShare(type, url, id, reaction) {
  href = "https://nedicom.ru/" + type + "/" + url;

  tgHref =
    "https://telegram.me/share/url?url=" +
    href +
    "&text=Смотри что нашел на nedicom.ru";

  vkHref =
    "https://vk.com/share.php?url=" + href +
    "&image=https://nedicom.ru/" +
    set.bundle.avatar_path +
    "&title=Статья с nedicom.ru. " +
    set.bundle.header;

  okHref =
    "https://connect.ok.ru/offer?url=" + href +
    "&title=Статья с nedicom.ru. " +
    set.bundle.header +
    "&imageUrl=https://nedicom.ru/" +
    set.bundle.avatar_path;

  if (reaction == "up") {
    submit(type, id, "shares", reaction);
  }
}

function shareTg() {
  window.open(tgHref, "_blank");
}

const hrefCopy = () => {
  navigator.clipboard.writeText(href);
  iconCopy.value = false;
};

const shareVK = () => {
  window.open(vkHref, "_blank");
};

const shareOK = () => {
  window.open(okHref, "_blank");
};
</script>


<template>
  <div class="flex w-full">
    <!-- like button-->
    <span class="flex items-center text-gray-500 hover:text-gray-700 cursor-pointer mr-1">
      <span v-if="!set.auth">
        <svg @click="invislike = !invislike" class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
          width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
        </svg>

        <div role="tooltip" :class="{ 'invisible': invislike }" @mouseleave="invislike = !invislike" class="absolute z-50 max-w-[90vw] w-64 -translate-x-1/2 left-1/2 sm:left-auto sm:translate-x-0
            text-sm text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm">
          <div class="px-3 py-2">
            <p>Нужно
              <a href="/login" class="text-blue-700 cursor-pointer hover:underline">войти</a>
              на сайт, иначе мы не поймем кто лайкнул.
            </p>
          </div>
          <div data-popper-arrow></div>
        </div>

      </span>

      <span v-else>
        <svg v-if="bundle.user_like" @click="submit(bundle.type, bundle.id, 'likes', 'down')" class="w-6 h-6"
          aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
          viewBox="0 0 24 24">
          <path
            d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z" />
        </svg>

        <svg v-else @click="submit(bundle.type, bundle.id, 'likes', 'up')" class="w-6 h-6" aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
        </svg>


      </span>

      <span v-if="bundle.likes > 0" class="text-sm">{{ bundle.likes }}</span>
    </span>
    <!-- like button-->

    <!--share btn-->
    <span v-if="bundle.user_share"
      @click="invisshare = !invisshare; setShare(bundle.type, bundle.url, bundle.id, 'nothing')"
      class="flex items-center text-gray-500 hover:text-gray-700 cursor-pointer mr-1">
      <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
        viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12 4 4m-4-4L8 8" />
      </svg>
      <span v-if="bundle.shares > 0" class="text-sm">{{ bundle.shares }}</span>
    </span>

    <span v-else @click="invisshare = !invisshare; setShare(bundle.type, bundle.url, bundle.id, 'up')"
      class="flex items-center text-gray-500 hover:text-gray-700 cursor-pointer mr-1">
      <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
        viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12 4 4m-4-4L8 8" />
      </svg>
      <span v-if="bundle.shares > 0" class="text-sm">{{ bundle.shares }}</span>
    </span>

    <!--share tooltip-->
    <div role="tooltip" :class="{ 'invisible': invisshare }" @mouseleave="invisshare = !invisshare"
      class="absolute z-50 inline-block text-sm text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm">
      <div class="px-3 py-2">
        <!--copy text-->
        <p @click="hrefCopy()" class="hover:underline cursor-pointer">
          <span v-if="iconCopy" class="flex">
            <svg class="w-[20px] h-[20px] mr-1 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961" />
            </svg>копировать ссылку
          </span>

          <span v-else class="flex">
            <svg class="w-[20px] h-[20px] mr-1 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            скопировано</span>
        </p>
        <!--copy text-->

        <!--tg-->
        <p class="hover:underline cursor-pointer flex">
          <svg class="w-[20px] h-[20px] mr-1 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            width="24" height="24" viewBox="0 0 25 25">
            <path fill-rule="evenodd"
              d="M1.581 11.749c6.174-2.54 10.291-4.215 12.351-5.024 5.882-2.31 7.104-2.712 7.9-2.725.175-.003.567.038.82.233.215.164.274.386.302.541.029.156.064.51.036.788-.319 3.162-1.698 10.837-2.4 14.379-.296 1.498-.881 2-1.447 2.05-1.23.107-2.164-.767-3.355-1.505-1.864-1.154-2.917-1.872-4.726-2.998-2.091-1.301-.736-2.016.456-3.185.312-.306 5.73-4.96 5.835-5.383.013-.053.026-.25-.098-.354-.124-.104-.307-.068-.439-.04-.187.04-3.164 1.899-8.933 5.576-.845.548-1.61.815-2.297.801-.756-.015-2.21-.403-3.292-.735-1.326-.407-2.38-.623-2.288-1.314.047-.36.573-.729 1.575-1.105Z"
              clip-rule="evenodd"></path>
          </svg>
          <button @click="shareTg()" target="_blank">поделиться в tg</button>
        </p>
        <!--tg-->

        <!--vk-->
        <p @click="shareVK()" type="button" aria-label="поделиться статьей в социальной сети ВКонтакте"
          class="flex items-center hover:underline cursor-pointer">
          <svg class="w-5 h-5 mr-1 fill-cyan-500 hover:fill-cyan-700" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path
              d="M15.07 2H8.93C3.33 2 2 3.33 2 8.93v6.14C2 20.67 3.33 22 8.93 22h6.14c5.6 0 6.93-1.33 6.93-6.93V8.93C22 3.33 20.67 2 15.07 2m3.08 14.27h-1.46c-.55 0-.72-.45-1.69-1.44c-.88-.83-1.26-.95-1.47-.95c-.29 0-.38.08-.38.5v1.31c0 .35-.11.57-1.04.57c-1.54 0-3.25-.94-4.45-2.67c-1.81-2.54-2.3-4.46-2.3-4.84c0-.21.07-.41.49-.41h1.47c.37 0 .51.16.65.56c.72 2.1 1.92 3.9 2.41 3.9c.19 0 .27-.09.27-.55V10.1c-.05-.98-.58-1.07-.58-1.42c0-.18.14-.34.37-.34h2.29c.31 0 .42.16.42.54v2.89c0 .31.13.42.23.42c.18 0 .34-.11.67-.45c1.05-1.17 1.8-2.98 1.8-2.98c.1-.21.26-.41.65-.41h1.43c.44 0 .54.23.44.54c-.18.85-1.96 3.36-1.94 3.36c-.16.25-.22.36 0 .65c.15.21.66.65 1 1.04c.62.71 1.1 1.3 1.23 1.71c.11.41-.09.62-.51.62z" />
          </svg>
          <span>поделиться в ВК</span>
        </p>
        <!--vk-->

        <!--ok-->
        <p @click="shareOK()" type="button" aria-label="поделиться статьей в социальной сети Одноклассники"
          class="flex items-center hover:underline cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
            class="w-5 h-5 mr-1 fill-amber-500 hover:fill-amber-600" viewBox="0 0 48 48">
            <path d="M42,37c0,2.8-2.2,5-5,5H11c-2.8,0-5-2.2-5-5V11c0-2.8,2.2-5,5-5h26c2.8,0,5,2.2,5,5V37z"></path>
            <path fill="#FFF"
              d="M26.9,30.4c1.5-0.3,2.9-0.9,4.1-1.7c1-0.6,1.3-1.9,0.7-2.9c-0.6-1-1.9-1.3-2.9-0.7c-2.9,1.8-6.7,1.8-9.6,0c-1-0.6-2.3-0.3-2.9,0.7c-0.6,1-0.3,2.3,0.7,2.9c1.3,0.8,2.7,1.4,4.1,1.7l-4,4c-0.8,0.8-0.8,2.1,0,3c0.4,0.4,0.9,0.6,1.5,0.6c0.5,0,1.1-0.2,1.5-0.6l3.9-3.9l3.9,3.9c0.8,0.8,2.1,0.8,3,0c0.8-0.8,0.8-2.1,0-3C30.9,34.4,26.9,30.4,26.9,30.4z M24,10c-3.9,0-7,3.1-7,7c0,3.9,3.1,7,7,7c3.9,0,7-3.1,7-7C31,13.1,27.9,10,24,10z M24,20c-1.7,0-3-1.3-3-3c0-1.7,1.3-3,3-3c1.7,0,3,1.3,3,3C27,18.7,25.7,20,24,20z">
            </path>
          </svg>
          <span>поделиться в ОК</span>
        </p>
        <!--ok-->
      </div>
      <div data-popper-arrow></div>
    </div>
    <!--share tooltip-->
    <!--share btn-->

    <!--bookmarks-->
    <!--non auth-->
    <span class="flex items-center">
      <span v-if="!set.auth">
        <span @click="invisbookmrks = !invisbookmrks"
          class="flex items-center text-gray-500 hover:text-gray-700 cursor-pointer">
          <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m17 21-5-4-5 4V3.889a.92.92 0 0 1 .244-.629.808.808 0 0 1 .59-.26h8.333a.81.81 0 0 1 .589.26.92.92 0 0 1 .244.63V21Z" />
          </svg><span v-if="bundle.bookmarks > 0" class="text-sm">{{
            bundle.bookmarks
          }}</span>
        </span>

        <div role="tooltip" :class="{ 'invisible': invisbookmrks }" @mouseleave="invisbookmrks = !invisbookmrks"
          class="absolute z-50 max-w-[90vw] w-64 -translate-x-1/2 left-1/2 sm:left-auto sm:translate-x-0
            text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm">
          <div class="px-3 py-2">
            <p>Нужно
              <a href="/login" class="text-blue-700 cursor-pointer hover:underline">войти</a>
              на сайт, тогда у Вас появится раздел с избранным.
            </p>
          </div>
          <div data-popper-arrow></div>
        </div>
      </span>

      <!--auth-->
      <span v-else>
        <!--auth has vote-->
        <span v-if="bundle.user_bookmark" @click="submit(bundle.type, bundle.id, 'bookmarks', 'down')"
          class="flex items-center text-gray-500 hover:text-gray-700 cursor-pointer">
          <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="currentColor" viewBox="0 0 24 24">
            <path
              d="M7.833 2c-.507 0-.98.216-1.318.576A1.92 1.92 0 0 0 6 3.89V21a1 1 0 0 0 1.625.78L12 18.28l4.375 3.5A1 1 0 0 0 18 21V3.889c0-.481-.178-.954-.515-1.313A1.808 1.808 0 0 0 16.167 2H7.833Z" />
          </svg><span v-if="bundle.bookmarks > 0" class="text-sm">{{
            bundle.bookmarks
          }}</span>
        </span>

        <!--auth has no vote-->
        <span v-else @click="submit(bundle.type, bundle.id, 'bookmarks', 'up')"
          class="flex items-center text-gray-500 hover:text-gray-700 cursor-pointer">
          <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m17 21-5-4-5 4V3.889a.92.92 0 0 1 .244-.629.808.808 0 0 1 .59-.26h8.333a.81.81 0 0 1 .589.26.92.92 0 0 1 .244.63V21Z" />
          </svg><span v-if="bundle.bookmarks > 0" class="text-sm">{{
            bundle.bookmarks
          }}</span>
        </span>
      </span>
    </span>
    <!--bookmarks-->
  </div>
</template>