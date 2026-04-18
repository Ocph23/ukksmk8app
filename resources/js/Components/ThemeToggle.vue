<script setup>
import { ref, onMounted } from 'vue'
import { Switch } from '@/components/ui/switch'

const isDark = ref(false)

function toggleTheme() {
  isDark.value = !isDark.value
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
  applyTheme()
}

function applyTheme() {
  if (isDark.value) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

onMounted(() => {
  // Check localStorage first
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme) {
    isDark.value = savedTheme === 'dark'
  } else {
    // Check system preference
    isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
  }
  applyTheme()
})
</script>

<template>
  <div class="flex items-center gap-2">
    <!-- Sun icon for light mode -->
    <svg
      v-if="!isDark"
      xmlns="http://www.w3.org/2000/svg"
      class="h-4 w-4 text-yellow-500"
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
      />
    </svg>
    
    <!-- Moon icon for dark mode -->
    <svg
      v-else
      xmlns="http://www.w3.org/2000/svg"
      class="h-4 w-4 text-blue-400"
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
      />
    </svg>

    <Switch
      :checked="isDark"
      @update:checked="toggleTheme"
      class="cursor-pointer"
    />
  </div>
</template>
