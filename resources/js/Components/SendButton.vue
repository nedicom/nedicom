 <template>
  <button
        type="submit"
        class="
          my-5
          inline-flex
          items-center
          px-6 py-3.5
          text-base font-medium
          text-center text-white
          bg-blue-700
          rounded-lg
          focus:ring-4 focus:ring-blue-200
          dark:focus:ring-blue-900
          hover:bg-blue-800
          disabled:opacity-25
        "
      >
    <slot />
  </button>
</template>