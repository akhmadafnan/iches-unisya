<script setup lang="ts">
import { computed, type HTMLAttributes } from 'vue';
import { DialogContent, DialogOverlay, DialogPortal, useForwardPropsEmits, type DialogContentEmits, type DialogContentProps } from 'reka-ui';
import { cn } from '@/lib/utils';

defineOptions({ inheritAttrs: false });
const props = defineProps<DialogContentProps & { class?: HTMLAttributes['class'] }>();
const emits = defineEmits<DialogContentEmits>();
const delegatedProps = computed(() => {
    const { class: _, ...contentProps } = props;
    return contentProps;
});
const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <DialogPortal>
        <DialogOverlay data-slot="dialog-overlay" class="fixed inset-0 z-40 bg-black/45" />
        <DialogContent v-bind="{ ...$attrs, ...forwarded }" data-slot="dialog-content" :class="cn('fixed left-1/2 top-1/2 z-50 grid max-h-[calc(100dvh-2rem)] w-full max-w-[calc(100%-2rem)] -translate-x-1/2 -translate-y-1/2 gap-4 overflow-y-auto overscroll-contain rounded-none bg-popover p-4 text-xs/relaxed text-popover-foreground outline-none ring-1 ring-foreground/10 sm:max-w-sm', props.class)"><slot /></DialogContent>
    </DialogPortal>
</template>
