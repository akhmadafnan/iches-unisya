<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { Locale } from '@/types';
defineProps<{ eventName: string }>();
const { t, locale } = useI18n();
const languages: { value: Locale; label: string }[] = [
    { value: 'en', label: 'English' }, { value: 'id', label: 'Bahasa Indonesia' }, { value: 'ar', label: 'العربية' },
];
const switching = ref(false);
const failed = ref(false);
async function changeLanguage(event: Event) {
    const select = event.target as HTMLSelectElement;
    switching.value = true;
    failed.value = false;
    try {
        const token = document.cookie.split('; ').find(cookie => cookie.startsWith('XSRF-TOKEN='))?.slice('XSRF-TOKEN='.length);
        // The existing locale endpoint redirects to auth pages; keep that redirect out of public navigation.
        const response = await fetch('/locale', {
            method: 'POST', credentials: 'same-origin', redirect: 'manual', cache: 'no-store',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json',
                ...(token ? { 'X-XSRF-TOKEN': decodeURIComponent(token) } : {}) },
            body: JSON.stringify({ locale: select.value }),
        });
        if (response.type !== 'opaqueredirect') throw new Error('Locale change was not confirmed.');
        router.reload({
            onError: () => { failed.value = true; },
            onFinish: () => { switching.value = false; },
        });
    } catch {
        failed.value = true;
        switching.value = false;
        select.value = locale.value;
    }
}
</script>

<template>
    <div class="public-cms min-h-svh bg-[#F7F6F1] text-[#17211C]" :lang="locale" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
        <a href="#public-content" class="skip-link">{{ t('skip') }}</a>
        <header class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-6 border-b border-[#B79A5B] px-6 py-6 sm:px-10">
            <p class="max-w-2xl text-sm font-semibold text-[#164734]">{{ eventName }}</p>
            <label class="flex items-center gap-3 text-sm">
                <span>{{ t('language') }}</span>
                <select :value="locale" :disabled="switching" :aria-busy="switching" class="max-w-full rounded-none border border-[#164734] bg-transparent px-3 py-2" @change="changeLanguage">
                    <option v-for="language in languages" :key="language.value" :value="language.value" :lang="language.value">{{ language.label }}</option>
                </select>
            </label>
            <p v-if="failed" role="alert" class="w-full text-sm">{{ t('failure') }}</p>
        </header>
        <main id="public-content" tabindex="-1" class="mx-auto max-w-6xl px-6 py-12 sm:px-10 sm:py-20">
            <slot />
        </main>
    </div>
</template>

<style scoped>
.public-cms { font-family: 'Inter Variable', 'Noto Sans Arabic Variable', sans-serif; overflow-wrap: anywhere; }
.public-cms :deep(h1), .public-cms :deep(h2) { font-family: 'Sora Variable', 'Noto Sans Arabic Variable', sans-serif; }
.public-cms :deep(a:focus-visible), .public-cms :deep(select:focus-visible), .public-cms :deep(main:focus-visible) { outline: 2px solid #164734; outline-offset: 5px; }
.skip-link { position: fixed; inset-inline-start: 1rem; top: -8rem; z-index: 50; padding: 1rem; background: #f7f6f1; }
.skip-link:focus { top: 1rem; }
</style>
