<script setup lang="ts">
import { DialogContent, DialogOverlay, DialogPortal } from 'reka-ui';
</script>

<template>
    <DialogPortal>
        <DialogOverlay class="fixed inset-0 z-40 bg-black/45" />
        <DialogContent data-slot="sheet-content" class="fixed inset-y-0 start-0 z-50 flex w-80 max-w-[90vw] flex-col overflow-y-auto overscroll-contain border-e border-border bg-card p-5 text-foreground shadow-xl">
            <slot />
        </DialogContent>
    </DialogPortal>
</template>
