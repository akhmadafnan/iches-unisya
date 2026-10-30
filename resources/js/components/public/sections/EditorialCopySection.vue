<script setup lang="ts">
import type { SectionOf } from '@/lib/public-cms/types';
defineProps<{ section: SectionOf<'editorial-copy'> }>();
</script>

<template>
    <p class="text-lg leading-loose whitespace-pre-line" :class="section.variant === 'wide' ? 'max-w-none' : 'max-w-2xl'">{{ section.content.body }}</p>
</template>
