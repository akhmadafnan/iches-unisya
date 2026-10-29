<script setup lang="ts">
import { Primitive, type PrimitiveProps } from 'reka-ui';
import { cva } from 'class-variance-authority';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

// shadcn-vue's Primitive/CVA button pattern, scoped to the shell's variants.
interface Props extends PrimitiveProps { variant?: 'default' | 'outline' | 'ghost'; class?: ClassValue }
const props = withDefaults(defineProps<Props>(), { as: 'button', variant: 'default' });
const variants = cva('inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors disabled:pointer-events-none disabled:opacity-50 [&_svg]:size-4 [&_svg]:shrink-0', {
    variants: { variant: {
        default: 'bg-primary text-primary-foreground hover:bg-primary/90',
        outline: 'border border-border bg-card text-foreground hover:bg-muted',
        ghost: 'text-inherit hover:bg-current/10',
    } },
});
</script>

<template>
    <Primitive data-slot="button" :as="as" :as-child="asChild" :class="twMerge(clsx(variants({ variant }), props.class))"><slot /></Primitive>
</template>
