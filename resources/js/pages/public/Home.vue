<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import PublicCmsLayout from '@/components/public/PublicCmsLayout.vue';

const { t } = useI18n();
</script>

<template>
    <Head :title="t('publicHome.title')" />
    <PublicCmsLayout event-name="ICHES UNISYA 2027">
        <div class="grid gap-12 py-8 sm:py-16 lg:grid-cols-[2fr_1fr] lg:gap-20">
            <div>
                <p class="mb-8 text-sm font-medium tracking-widest text-[#164734]">{{ t('publicHome.kicker') }}</p>
                <h1 class="max-w-4xl text-4xl leading-tight font-medium tracking-tight text-[#164734] sm:text-6xl lg:text-7xl">{{ t('publicHome.title') }}</h1>
            </div>
            <div class="self-end border-t border-[#B79A5B] pt-6">
                <h2 class="text-xl leading-relaxed font-semibold">{{ t('publicHome.preparing') }}</h2>
                <p class="mt-5 max-w-prose text-base leading-loose text-[#46554B]">{{ t('publicHome.body') }}</p>
            </div>
        </div>
    </PublicCmsLayout>
</template>
