<script setup lang="ts">
defineOptions({ inheritAttrs: false })
</script>

<template><option v-bind="$attrs"><slot /></option></template>
