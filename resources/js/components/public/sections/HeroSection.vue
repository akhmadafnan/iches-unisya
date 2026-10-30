<script setup lang="ts">
import type { SectionOf } from '@/lib/public-cms/types';
defineProps<{ section: SectionOf<'hero'> }>();
</script>

<template>
    <div :class="section.variant === 'split' ? 'grid items-start gap-8 lg:grid-cols-[3fr_2fr] lg:gap-20' : 'max-w-4xl space-y-8'">
        <h2 class="text-3xl leading-tight font-medium tracking-tight text-[#164734] sm:text-5xl">{{ section.content.heading }}</h2>
        <p class="max-w-prose border-s-2 border-[#B79A5B] ps-6 text-lg leading-loose whitespace-pre-line text-[#46554B] sm:ps-8">{{ section.content.body }}</p>
    </div>
</template>
