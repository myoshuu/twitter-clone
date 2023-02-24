<script setup>
import Sidebar from "./Sidebar.vue";
import Filter from "./Filter.vue";
</script>

<template>
  <div class="flex justify-center w-[100vw] h-[100vh] overflow-hidden">
    <div class="w-11/12 grid grid-cols-[1fr_2fr_1fr]">
      <Sidebar />
      <slot />
      <Filter />
    </div>
  </div>
</template>
