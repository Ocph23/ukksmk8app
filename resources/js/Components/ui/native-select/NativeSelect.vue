<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { ChevronDown } from 'lucide-vue-next'
import { cn } from '@/lib/utils'

defineOptions({ inheritAttrs: false })
const props = withDefaults(defineProps<{ class?: HTMLAttributes['class'] }>(), { class: '' })
const model = defineModel<string | number | null>()
</script>

<template>
  <div class="relative w-full">
    <select
      v-bind="$attrs"
      :value="model"
      @change="model = ($event.target as HTMLSelectElement).value"
      :class="cn(
        'border-input bg-background text-foreground focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:bg-input/30 dark:hover:bg-input/50 flex h-9 w-full appearance-none items-center rounded-lg border px-3 py-1.5 pr-8 text-sm shadow-xs transition-colors outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50',
        props.class,
      )"
    >
      <slot />
    </select>
    <ChevronDown class="text-muted-foreground pointer-events-none absolute top-1/2 right-3 size-4 -translate-y-1/2" aria-hidden="true" />
  </div>
</template>
