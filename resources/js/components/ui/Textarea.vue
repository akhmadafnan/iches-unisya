<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{ class?: HTMLAttributes['class'] }>();
const model = defineModel<string | number>();
</script>

<template>
    <textarea v-model="model" data-slot="textarea" :class="cn('flex min-h-16 w-full min-w-0 rounded-none border border-input bg-transparent px-2.5 py-2 text-xs outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-1 focus-visible:ring-ring/50 aria-invalid:border-destructive aria-invalid:ring-1 aria-invalid:ring-destructive/20 disabled:cursor-not-allowed disabled:bg-input/50 disabled:opacity-50', props.class)" />
</template>
