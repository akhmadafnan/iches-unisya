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
    <Head :title="cms.seo.title">
        <meta head-key="public-robots" name="robots" content="noindex, nofollow" />
        <meta v-if="cms.seo.description" head-key="description" name="description" :content="cms.seo.description" />
        <link v-if="cms.seo.canonical" head-key="canonical" rel="canonical" :href="cms.seo.canonical" />
        <link v-for="(url, language) in cms.seo.alternates" :key="language" :head-key="`alternate-${language}`" rel="alternate" :hreflang="language" :href="url" />
        <meta head-key="og-title" property="og:title" :content="cms.seo.title" />
        <meta v-if="cms.seo.description" head-key="og-description" property="og:description" :content="cms.seo.description" />
        <meta v-if="cms.seo.canonical" head-key="og-url" property="og:url" :content="cms.seo.canonical" />
        <meta head-key="og-locale" property="og:locale" :content="cms.seo.og_locale" />
        <meta v-if="cms.seo.image" head-key="og-image" property="og:image" :content="cms.seo.image" />
    </Head>
    <PublicCmsLayout :event-name="cms.event.name" :language-urls="cms.seo.language_urls">
        <p v-if="cms.page.fallback" class="mb-4 text-sm text-[#164734]">{{ t('publicCms.englishFallback') }}</p>
        <h1 class="max-w-5xl border-b border-[#B79A5B] pb-10 text-4xl leading-tight font-medium tracking-tight text-[#164734] sm:pb-16 sm:text-6xl lg:text-7xl" :lang="cms.page.locale" :dir="cms.page.locale === 'ar' ? 'rtl' : 'ltr'">{{ cms.page.title }}</h1>
        <div class="mt-4 divide-y divide-[#164734]/20">
            <SectionRenderer v-for="section in cms.sections" :key="section.id" :section="section" :catalog="cms.catalog" />
        </div>
    </PublicCmsLayout>
</template>
