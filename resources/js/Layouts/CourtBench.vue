<template>
  <section class="relative w-full overflow-hidden" style="height: 320px;">

    <!-- SVG фон — схема коридора суда -->
    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1200 320" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

      <!-- Потолок -->
      <rect x="0" y="0" width="1200" height="320" fill="#F0EAE0"/>

      <!-- Задняя стена -->
      <rect x="0" y="0" width="1200" height="230" fill="#C8AD8A"/>

      <!-- Деревянные панели на стене -->
      <rect x="0"    y="140" width="1200" height="8"  fill="#A07850" opacity="0.5"/>
      <rect x="0"    y="80"  width="1200" height="4"  fill="#A07850" opacity="0.3"/>
      <!-- Вертикальные филёнки -->
      <rect x="149"  y="0"   width="4"   height="148" fill="#A07850" opacity="0.3"/>
      <rect x="299"  y="0"   width="4"   height="148" fill="#A07850" opacity="0.3"/>
      <rect x="499"  y="0"   width="4"   height="148" fill="#A07850" opacity="0.3"/>
      <rect x="699"  y="0"   width="4"   height="148" fill="#A07850" opacity="0.3"/>
      <rect x="899"  y="0"   width="4"   height="148" fill="#A07850" opacity="0.3"/>
      <rect x="1049" y="0"   width="4"   height="148" fill="#A07850" opacity="0.3"/>

      <!-- Окна -->
      <rect x="50"   y="20" width="80" height="120" rx="40" fill="#D4EAF7" opacity="0.8"/>
      <rect x="320"  y="20" width="80" height="120" rx="40" fill="#D4EAF7" opacity="0.8"/>
      <rect x="720"  y="20" width="80" height="120" rx="40" fill="#D4EAF7" opacity="0.8"/>
      <rect x="1070" y="20" width="80" height="120" rx="40" fill="#D4EAF7" opacity="0.8"/>

      <!-- Дверь в зал заседаний (слева) -->
      <rect x="170" y="50" width="110" height="180" rx="4" fill="#5C3010"/>
      <rect x="175" y="55" width="100" height="170" rx="3" fill="#7A4520"/>
      <!-- Ручка двери -->
      <circle cx="268" cy="145" r="5" fill="#C9A84C"/>
      <!-- Табличка на двери -->
      <rect x="195" y="65" width="60" height="20" rx="2" fill="#C9A84C" opacity="0.7"/>

      <!-- Колонны -->
      <rect x="130" y="0"   width="22" height="230" fill="#B8967A"/>
      <rect x="460" y="0"   width="22" height="230" fill="#B8967A"/>
      <rect x="720" y="0"   width="22" height="230" fill="#B8967A"/>
      <rect x="1048" y="0"  width="22" height="230" fill="#B8967A"/>
      <!-- Капители колонн -->
      <rect x="122" y="0"   width="38" height="14" fill="#C9A84C" opacity="0.6"/>
      <rect x="452" y="0"   width="38" height="14" fill="#C9A84C" opacity="0.6"/>
      <rect x="712" y="0"   width="38" height="14" fill="#C9A84C" opacity="0.6"/>
      <rect x="1040" y="0"  width="38" height="14" fill="#C9A84C" opacity="0.6"/>

      <!-- Пол -->
      <rect x="0" y="230" width="1200" height="90" fill="#DDD0B8"/>
      <!-- Плитка пола -->
      <line x1="0"   y1="230" x2="1200" y2="230" stroke="#C4B49A" stroke-width="2"/>
      <line x1="0"   y1="260" x2="1200" y2="260" stroke="#C4B49A" stroke-width="1" opacity="0.5"/>
      <line x1="0"   y1="290" x2="1200" y2="290" stroke="#C4B49A" stroke-width="1" opacity="0.5"/>
      <line x1="200" y1="230" x2="200" y2="320" stroke="#C4B49A" stroke-width="1" opacity="0.5"/>
      <line x1="400" y1="230" x2="400" y2="320" stroke="#C4B49A" stroke-width="1" opacity="0.5"/>
      <line x1="600" y1="230" x2="600" y2="320" stroke="#C4B49A" stroke-width="1" opacity="0.5"/>
      <line x1="800" y1="230" x2="800" y2="320" stroke="#C4B49A" stroke-width="1" opacity="0.5"/>
      <line x1="1000" y1="230" x2="1000" y2="320" stroke="#C4B49A" stroke-width="1" opacity="0.5"/>

      <!-- Скамья в коридоре -->
      <rect x="60" y="218" width="1080" height="20" rx="3" fill="#8B5E3C"/>
      <rect x="60" y="235" width="1080" height="8"  rx="2" fill="#6B4423"/>
      <!-- Ножки скамьи -->
      <rect x="100"  y="238" width="12" height="30" fill="#6B4423"/>
      <rect x="280"  y="238" width="12" height="30" fill="#6B4423"/>
      <rect x="480"  y="238" width="12" height="30" fill="#6B4423"/>
      <rect x="680"  y="238" width="12" height="30" fill="#6B4423"/>
      <rect x="880"  y="238" width="12" height="30" fill="#6B4423"/>
      <rect x="1080" y="238" width="12" height="30" fill="#6B4423"/>

      <!-- Весы на стене (центр) -->
      <circle cx="600" cy="50" r="32" fill="#C9A84C" opacity="0.15"/>
      <circle cx="600" cy="50" r="28" fill="none" stroke="#C9A84C" stroke-width="2" opacity="0.4"/>
      <line x1="600" y1="30" x2="600" y2="70" stroke="#C9A84C" stroke-width="2" opacity="0.5"/>
      <line x1="576" y1="42" x2="624" y2="42" stroke="#C9A84C" stroke-width="2" opacity="0.5"/>
      <circle cx="600" cy="42" r="3" fill="#C9A84C" opacity="0.6"/>
      <line x1="576" y1="42" x2="576" y2="55" stroke="#C9A84C" stroke-width="1.5" opacity="0.5"/>
      <line x1="624" y1="42" x2="624" y2="55" stroke="#C9A84C" stroke-width="1.5" opacity="0.5"/>
      <path d="M569 56 Q576 60 583 56" stroke="#C9A84C" stroke-width="1.5" fill="none" opacity="0.5"/>
      <path d="M617 56 Q624 60 631 56" stroke="#C9A84C" stroke-width="1.5" fill="none" opacity="0.5"/>

    </svg>

    <!-- Юристы — ряд кружков поверх скамьи -->
    <div class="absolute bottom-10 left-0 right-0 flex justify-center gap-8 px-8">
      <div
        v-for="lawyer in lawyers?.slice(0, 6)"
        :key="lawyer.id"
        class="relative group flex flex-col items-center"
      >
        <!-- Hover карточка -->
        <div class="absolute bottom-full mb-3 left-1/2 -translate-x-1/2 w-44 bg-white rounded-xl shadow-2xl p-3 z-50
                    opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200">
          <img
            :src="lawyer.avatar_path ? 'https://nedicom.ru/' + lawyer.avatar_path : '/default-avatar.jpg'"
            class="w-14 h-14 rounded-full mx-auto mb-2 object-cover ring-2 ring-amber-200"
          />
          <p class="text-xs font-bold text-gray-900 text-center">{{ lawyer.name }}</p>
          <p class="text-xs text-gray-400 text-center mb-3">{{ lawyer.specialization ?? 'Юрист' }}</p>
          <button class="w-full bg-blue-700 text-white text-xs font-semibold py-2 rounded-lg">
            📞 Запросить звонок
          </button>
        </div>

        <!-- Фото юриста -->
        <img
          :src="lawyer.avatar_path ? 'https://nedicom.ru/' + lawyer.avatar_path : '/default-avatar.jpg'"
          :alt="lawyer.name"
          class="w-16 h-16 rounded-full object-cover ring-3 ring-white shadow-lg
                 group-hover:-translate-y-2 transition-transform duration-200"
        />
        <span class="mt-1 text-xs text-amber-900 font-medium">{{ lawyer.name.split(' ')[0] }}</span>
      </div>
    </div>

  </section>
</template>

<script setup>
defineProps({ lawyers: Object });
</script>
