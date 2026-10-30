<script setup lang="ts">
import type { SectionOf } from '@/lib/public-cms/types';
defineProps<{ section: SectionOf<'cta'> }>();
</script>

<template>
    <a :href="section.content.href" class="inline-block max-w-full border-b-2 border-[#B79A5B] pb-2 text-lg font-semibold text-[#164734] underline-offset-4 hover:underline">{{ section.content.label }} <span aria-hidden="true">↗</span></a>
</template>
