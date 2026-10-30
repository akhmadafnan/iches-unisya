<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import PublicCmsLayout from '@/components/public/PublicCmsLayout.vue';
import SectionRenderer from '@/components/public/SectionRenderer.vue';
import type { PublicCms } from '@/lib/public-cms/types';
defineProps<{ cms: PublicCms }>();
const { t } = useI18n();
</script>

<template>
    <Head :title="cms.page.title" />
    <PublicCmsLayout :event-name="cms.event.name">
        <p v-if="cms.page.fallback" class="mb-4 text-sm text-[#164734]">{{ t('publicCms.englishFallback') }}</p>
        <h1 class="max-w-5xl border-b border-[#B79A5B] pb-10 text-4xl leading-tight font-medium tracking-tight text-[#164734] sm:pb-16 sm:text-6xl lg:text-7xl" :lang="cms.page.locale" :dir="cms.page.locale === 'ar' ? 'rtl' : 'ltr'">{{ cms.page.title }}</h1>
        <div class="mt-4 divide-y divide-[#164734]/20">
            <SectionRenderer v-for="section in cms.sections" :key="section.id" :section="section" :catalog="cms.catalog" />
        </div>
    </PublicCmsLayout>
</template>
