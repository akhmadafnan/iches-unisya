<script setup lang="ts">
import type { SectionOf } from '@/lib/public-cms/types';
defineProps<{ section: SectionOf<'editorial-copy'> }>();
</script>

<template>
    <p class="text-lg leading-loose whitespace-pre-line text-[#34473B] sm:text-xl" :class="section.variant === 'wide' ? 'max-w-none md:columns-2 md:gap-16' : 'max-w-3xl'">{{ section.content.body }}</p>
</template>
