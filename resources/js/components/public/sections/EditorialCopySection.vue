<script setup lang="ts">
import type { SectionOf } from '@/lib/public-cms/types';
defineProps<{ section: SectionOf<'editorial-copy'> }>();
</script>

<template>
    <p class="text-lg leading-loose whitespace-pre-line text-[#34473B] sm:text-xl" :class="section.variant === 'wide' ? 'max-w-none md:columns-2 md:gap-16' : 'max-w-3xl'">{{ section.content.body }}</p>

    <figure v-if="section.media" class="mt-8 overflow-hidden" :lang="section.media.locale" :dir="section.media.locale === 'ar' ? 'rtl' : 'ltr'">
        <img :src="section.media.url" :alt="section.media.alt" :style="{ objectPosition: `${section.media.focal.x}% ${section.media.focal.y}%` }" class="aspect-[16/9] w-full object-cover" loading="lazy" decoding="async" />
        <figcaption v-if="section.media.caption || section.media.credit" class="mt-3 text-sm leading-relaxed text-[#46554B]"><span v-if="section.media.caption">{{ section.media.caption }}</span><span v-if="section.media.caption && section.media.credit"> &middot; </span><span v-if="section.media.credit">{{ section.media.credit }}</span></figcaption>
    </figure>
</template>
