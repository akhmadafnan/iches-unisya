<script setup lang="ts">
import type { SectionOf } from '@/lib/public-cms/types';
defineProps<{ section: SectionOf<'hero'> }>();
</script>

<template>
    <div class="border-s-4 border-[#B79A5B] ps-6 sm:ps-10" :class="section.variant === 'split' ? 'grid gap-6 md:grid-cols-2 md:gap-12' : 'max-w-3xl space-y-6'">
        <h2 class="text-3xl leading-snug font-semibold text-[#164734] sm:text-4xl">{{ section.content.heading }}</h2>
        <p class="text-lg leading-relaxed whitespace-pre-line">{{ section.content.body }}</p>
    </div>
</template>
