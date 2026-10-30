<script setup lang="ts">
import { computed, type Component } from 'vue';
import { useI18n } from 'vue-i18n';
import { sectionRegistry } from '@/lib/public-cms/registry';
import type { PublicCatalog, PublicSection } from '@/lib/public-cms/types';
const props = defineProps<{ section: PublicSection; catalog: PublicCatalog }>();
const { t } = useI18n();
const renderer = computed<Component | null>(() => Object.hasOwn(sectionRegistry, props.section.component_key)
    && props.catalog[props.section.component_key]?.variants.includes(props.section.variant)
    ? sectionRegistry[props.section.component_key] : null);
</script>

<template>
    <section v-if="renderer" class="py-12 sm:py-20" :data-section="section.component_key" :data-variant="section.variant">
        <p v-if="section.fallback" class="mb-4 text-sm text-[#164734]">{{ t('publicCms.englishFallback') }}</p>
        <div :lang="section.locale" :dir="section.locale === 'ar' ? 'rtl' : 'ltr'">
            <component :is="renderer" :section="section" />
        </div>
    </section>
</template>
