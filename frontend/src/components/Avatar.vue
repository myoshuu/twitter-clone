<template>
  <div :class="`avatar ${placeholder}`">
    <div :class="`w-${imgW} rounded-full`">
      <img v-if="data" :src="imgSrc" />
      <div v-else>
        <div class="avatar placeholder">
          <div class="bg-grey text-neutral-content rounded-full w-16">
            <span class="text-xl text-darkGrey">{{ name }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    placeholder: {
      type: String,
    },

    imgW: {
      type: String,
    },

    data: {
      type: String,
    },

    imgSrc: {
      type: String,
    },

    name: {
      type: String,
    },
  },
};
</script>
