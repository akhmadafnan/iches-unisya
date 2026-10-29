<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { useI18n } from 'vue-i18n';
import type { SharedProps } from '../../types';
import Button from '../ui/Button.vue';

const page = usePage<SharedProps>();
const { t } = useI18n();
const labels = { en: 'English', id: 'Indonesia', ar: 'العربية' };
const form = useForm({ locale: page.props.locale });
watch(() => page.props.locale, (value) => { form.locale = value; });
</script>

<template>
    <form class="flex flex-wrap items-end gap-2 text-sm" :aria-busy="form.processing" @submit.prevent="form.post('/locale', { preserveScroll: true })">
        <label class="grid gap-1.5">
            <span class="text-xs text-muted-foreground">{{ t('language') }}</span>
            <select v-model="form.locale" class="shell-select" :disabled="form.processing" :aria-invalid="!!form.errors.locale">
                <option v-for="locale in page.props.supportedLocales" :key="locale" :value="locale" :lang="locale">{{ labels[locale] }}</option>
            </select>
        </label>
        <Button variant="outline" :disabled="form.processing || form.locale === page.props.locale">{{ form.processing ? t('saving') : t('apply') }}</Button>
        <p v-if="form.errors.locale" role="alert" class="w-full text-red-800">{{ t('failure') }}</p>
    </form>
</template>
