<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import type { SectionOf } from '@/lib/public-cms/types';
defineProps<{ section: SectionOf<'event-essentials'> }>();
const { t, locale } = useI18n();
</script>

<template>
    <h2 class="text-2xl leading-snug font-semibold text-[#164734]">{{ section.content.heading }}</h2>
    <dl v-if="Object.keys(section.bindings).length" class="mt-6 grid gap-6 sm:grid-cols-2">
        <div v-for="(value, field) in section.bindings" :key="field" class="border-t border-[#B79A5B] pt-3">
            <dt class="text-sm text-[#164734]" :lang="locale" :dir="locale === 'ar' ? 'rtl' : 'ltr'">{{ t(`publicCms.${field}`) }}</dt>
            <dd class="mt-2 leading-relaxed">
                <time v-if="(field === 'starts_at' || field === 'ends_at') && value" :datetime="value" dir="ltr">{{ value }}</time>
                <span v-else-if="value">{{ value }}</span>
                <p v-else :lang="locale" :dir="locale === 'ar' ? 'rtl' : 'ltr'">{{ t('publicCms.timeUnconfirmed') }}</p>
            </dd>
        </div>
    </dl>
</template>
