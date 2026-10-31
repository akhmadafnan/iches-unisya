<script setup lang="ts">
import type { SectionOf } from '@/lib/public-cms/types';
defineProps<{ section: SectionOf<'hero'> }>();
</script>

<template>
    <div :class="section.variant === 'split' ? 'grid items-start gap-8 lg:grid-cols-[3fr_2fr] lg:gap-20' : 'max-w-4xl space-y-8'">
        <h2 class="text-3xl leading-tight font-medium tracking-tight text-[#164734] sm:text-5xl">{{ section.content.heading }}</h2>
        <p class="max-w-prose border-s-2 border-[#B79A5B] ps-6 text-lg leading-loose whitespace-pre-line text-[#46554B] sm:ps-8">{{ section.content.body }}</p>
    </div>

    <figure v-if="section.media" class="mt-8 overflow-hidden" :lang="section.media.locale" :dir="section.media.locale === 'ar' ? 'rtl' : 'ltr'">
        <img :src="section.media.url" :alt="section.media.alt" :style="{ objectPosition: `${section.media.focal.x}% ${section.media.focal.y}%` }" class="aspect-[16/9] w-full object-cover" loading="lazy" decoding="async" />
        <figcaption v-if="section.media.caption || section.media.credit" class="mt-3 text-sm leading-relaxed text-[#46554B]"><span v-if="section.media.caption">{{ section.media.caption }}</span><span v-if="section.media.caption && section.media.credit"> &middot; </span><span v-if="section.media.credit">{{ section.media.credit }}</span></figcaption>
    </figure>
</template>
