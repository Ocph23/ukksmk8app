<script setup>
import { Toaster } from '@/components/ui/sonner'
</script>

<template>
  <slot />
  <Toaster
    position="top-right"
    :duration="4000"
    rich-colors
    close-button
  />
</template>
