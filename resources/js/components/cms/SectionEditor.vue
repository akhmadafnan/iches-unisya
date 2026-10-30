<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import type { Locale } from '@/types';
import type { ComponentDefinition, Section, TranslationStatus } from '@/lib/cms/schema';
import { setStatus, statuses, validField } from '@/lib/cms/editor';
import Button from '@/components/ui/Button.vue';
import NativeSelect from '@/components/ui/NativeSelect.vue';
import Textarea from '@/components/ui/Textarea.vue';
import Label from '@/components/ui/Label.vue';
import Badge from '@/components/ui/Badge.vue';

const section = defineModel<Section>({ required: true });
defineProps<{ definition: ComponentDefinition; locale: Locale; first: boolean; last: boolean; disabled: boolean; full: boolean }>();
defineEmits<{ action: [action: 'up' | 'down' | 'duplicate' | 'delete'] }>();
const { t } = useI18n();
</script>

<template>
    <section class="min-w-0 border border-border bg-card p-4 sm:p-5" :aria-labelledby="`${section.id}-heading`">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 :id="`${section.id}-heading`" tabindex="-1" class="font-brand text-sm font-semibold">{{ section.position + 1 }}. {{ t(`cms.components.${section.component_key}`) }}</h2>
            <Badge v-if="!section.visible" variant="secondary">{{ t('cms.hidden') }}</Badge>
        </div>
        <div class="mt-4 flex flex-wrap gap-2">
            <Button type="button" variant="outline" :disabled="disabled || first" :aria-label="t('cms.moveUpSection', { number: section.position + 1 })" @click="$emit('action', 'up')">{{ t('cms.up') }}</Button>
            <Button type="button" variant="outline" :disabled="disabled || last" :aria-label="t('cms.moveDownSection', { number: section.position + 1 })" @click="$emit('action', 'down')">{{ t('cms.down') }}</Button>
            <Button type="button" variant="outline" :disabled="disabled || full" @click="$emit('action', 'duplicate')">{{ t('cms.duplicate') }}</Button>
            <Button type="button" variant="ghost" :disabled="disabled" @click="$emit('action', 'delete')">{{ t('cms.remove') }}</Button>
        </div>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <div><Label :for="`${section.id}-variant`">{{ t('cms.variant') }}</Label><NativeSelect :id="`${section.id}-variant`" v-model="section.variant" :disabled="disabled"><option v-for="variant in definition.variants" :key="variant" :value="variant">{{ t(`cms.variants.${variant}`) }}</option></NativeSelect></div>
            <div class="flex items-center gap-2"><input :id="`${section.id}-visible`" v-model="section.visible" type="checkbox" class="size-4 accent-primary" :disabled="disabled" /><Label :for="`${section.id}-visible`">{{ t('cms.visible') }}</Label></div>
        </div>
        <p v-if="section.component_key === 'countdown'" class="mt-4 text-xs text-muted-foreground">{{ t('cms.countdownNote') }}</p>
        <p v-if="section.media_ids.length" class="mt-4 text-xs text-muted-foreground">{{ t('cms.mediaPreserved', { count: section.media_ids.length }) }}</p>
        <div class="mt-5" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
            <Label :for="`${section.id}-status`">{{ t('cms.translationStatus') }} · {{ locale.toUpperCase() }}</Label>
            <NativeSelect :id="`${section.id}-status`" :model-value="section.locales[locale].status" :disabled="disabled" @update:model-value="value => setStatus(section.locales, locale, value as TranslationStatus, Object.keys(definition.fields))"><option v-for="status in statuses" :key="status" :value="status">{{ t(`cms.status.${status}`) }}</option></NativeSelect>
            <div v-for="(type, field) in definition.fields" v-if="section.locales[locale].content" :key="field" class="mt-4">
                <Label :for="`${section.id}-${locale}-${field}`">{{ t(`cms.fields.${field}`) }}</Label>
                <Textarea :id="`${section.id}-${locale}-${field}`" v-model="section.locales[locale].content![field]" :maxlength="type === 'url' ? 2048 : 5000" :disabled="disabled" :dir="type === 'url' ? 'ltr' : undefined" :aria-invalid="!validField(section.locales[locale].content![field] ?? '', type, section.locales[locale].status)" :aria-describedby="`${section.id}-${locale}-${field}-help`" />
                <p :id="`${section.id}-${locale}-${field}-help`" class="mt-1 text-xs text-muted-foreground">{{ validField(section.locales[locale].content![field] ?? '', type, section.locales[locale].status) ? t(type === 'url' ? 'cms.urlHelp' : 'cms.textHelp') : t('cms.invalidField') }}</p>
            </div>
            <p v-else class="mt-3 text-xs text-muted-foreground">{{ t('cms.missingHelp') }}</p>
        </div>
    </section>
</template>
