<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import type { SectionOf } from '@/lib/public-cms/types';
defineProps<{ section: SectionOf<'countdown'> }>();
const { t, locale } = useI18n();
</script>

<template>
    <h2 class="text-2xl leading-snug font-semibold text-[#164734]">{{ section.content.heading }}</h2>
    <p class="mt-5 leading-relaxed" :lang="locale" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
        <template v-if="section.bindings.starts_at">
            {{ t('publicCms.starts_at') }}:
            <time :datetime="section.bindings.starts_at" dir="ltr">{{ section.bindings.starts_at }}</time>
        </template>
        <template v-else>{{ t('publicCms.timeUnconfirmed') }}</template>
    </p>
</template>
