<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cva } from 'class-variance-authority';
import { cn } from '@/lib/utils';

const props = withDefaults(defineProps<{
    variant?: 'default' | 'secondary' | 'destructive' | 'outline';
    class?: HTMLAttributes['class'];
}>(), { variant: 'default' });

const variants = cva('inline-flex h-5 w-fit shrink-0 items-center justify-center gap-1 whitespace-nowrap rounded-none border border-transparent px-2 py-0.5 text-xs font-medium', {
    variants: { variant: {
        default: 'bg-primary text-primary-foreground',
        secondary: 'bg-secondary text-secondary-foreground',
        destructive: 'bg-destructive/10 text-destructive',
        outline: 'border-border text-foreground',
    } },
});
</script>

<template>
    <span data-slot="badge" :data-variant="variant" :class="cn(variants({ variant }), props.class)"><slot /></span>
</template>
