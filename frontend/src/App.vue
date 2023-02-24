
<template>
  <RouterView />
</template>
<script>
export default {
  created() {
    this.axios.defaults.headers.common[
      "Authorization"
    ] = `Bearer ${localStorage.token}`;
  }
}
</script>