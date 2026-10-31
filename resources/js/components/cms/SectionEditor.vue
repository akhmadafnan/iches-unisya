<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import type { Locale } from '@/types';
import type { ComponentDefinition, Section, TranslationStatus, MediaItem } from '@/lib/cms/schema';
import { setStatus, statuses, validField, selectMedia } from '@/lib/cms/editor';
import Button from '@/components/ui/Button.vue';
import NativeSelect from '@/components/ui/NativeSelect.vue';
import Input from '@/components/ui/Input.vue';
import Textarea from '@/components/ui/Textarea.vue';
import Label from '@/components/ui/Label.vue';
import Badge from '@/components/ui/Badge.vue';

const section = defineModel<Section>({ required: true });
defineProps<{ mediaItems: MediaItem[]; definition: ComponentDefinition; locale: Locale; first: boolean; last: boolean; disabled: boolean; full: boolean }>();
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
        <p v-if="section.media_ids.length && !section.media" class="mt-4 text-xs text-muted-foreground">{{ t('cms.mediaPreserved', { count: section.media_ids.length }) }}</p>
        <fieldset v-if="['hero', 'editorial-copy'].includes(section.component_key)" class="mt-5 space-y-3 border border-border p-3" :disabled="disabled">
            <legend class="px-1 text-xs">{{ t('media.sectionImage') }}</legend>
            <Label :for="`${section.id}-image`">{{ t('media.choose') }}</Label>
            <NativeSelect :id="`${section.id}-image`" :model-value="section.media?.file_id ?? ''" @update:model-value="value => selectMedia(section, String(value))">
                <option value="">{{ t('media.none') }}</option>
                <option v-if="section.media && !mediaItems.some(item => item.id === section.media!.file_id)" :value="section.media.file_id">{{ section.media.file_id }}</option>
                <option v-for="item in mediaItems" :key="item.id" :value="item.id">{{ item.original_name }}</option>
            </NativeSelect>
            <template v-if="section.media">
                <Button type="button" variant="outline" @click="selectMedia(section, '')">{{ t('media.remove') }}</Button>
                <Label :for="`${section.id}-media-status`">{{ t('cms.translationStatus') }} &middot; {{ locale.toUpperCase() }}</Label>
                <NativeSelect :id="`${section.id}-media-status`" :model-value="section.media.locales[locale].status" @update:model-value="value => setStatus(section.media!.locales, locale, value as TranslationStatus, ['alt', 'caption', 'credit'])"><option v-for="status in statuses" :key="status" :value="status">{{ t(`cms.status.${status}`) }}</option></NativeSelect>
                <div v-for="field in ['alt', 'caption', 'credit']" v-if="section.media.locales[locale].content" :key="field" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
                    <Label :for="`${section.id}-media-${field}`">{{ t(`media.${field}`) }}</Label>
                    <Input :id="`${section.id}-media-${field}`" v-model="section.media.locales[locale].content![field]" maxlength="5000" :aria-describedby="`${section.id}-media-help`" :aria-invalid="!validField(section.media.locales[locale].content![field] ?? '', 'text', field === 'alt' ? section.media.locales[locale].status : 'draft')" />
                </div>
                <p :id="`${section.id}-media-help`" class="text-xs text-muted-foreground">{{ t('media.altHelp') }}</p>
                <div class="grid gap-3 sm:grid-cols-2"><div v-for="axis in (['x', 'y'] as const)" :key="axis"><Label :for="`${section.id}-focal-${axis}`">{{ t(`media.focal${axis.toUpperCase()}`) }}</Label><input :id="`${section.id}-focal-${axis}`" v-model.number="section.media.focal[axis]" type="range" min="0" max="100" step="1" class="block w-full accent-primary" dir="ltr" :disabled="disabled" /><output :for="`${section.id}-focal-${axis}`" class="text-xs tabular-nums">{{ section.media.focal[axis] }}%</output></div></div>
            </template>
        </fieldset>
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
