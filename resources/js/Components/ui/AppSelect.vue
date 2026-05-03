<template>
    <div class="space-y-1 w-full">
        <Label v-if="label" :for="id">{{ label }}</Label>
        <select
            :id="id"
            :value="modelValue"
            @input="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
            :class="[
                'w-full h-10 rounded-lg border border-input bg-background px-3 text-sm transition-colors outline-none',
                'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-2',
                'disabled:pointer-events-none disabled:opacity-50',
                $props.class
            ]"
        >
            <slot />
        </select>
    </div>
</template>

<script setup lang="ts">
import { Label } from '@/components/ui/label';
import type { HTMLAttributes } from 'vue';

interface Props {
    modelValue?: string | number;
    label?: string;
    id?: string;
    class?: HTMLAttributes['class'];
}

defineProps<Props>();
defineEmits<{
    (e: 'update:modelValue', value: string | number): void;
}>();
</script>
