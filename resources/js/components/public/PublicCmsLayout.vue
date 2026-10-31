<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { Locale } from '@/types';
const props = defineProps<{ eventName: string; languageUrls?: Record<Locale, string> }>();
const { t, locale } = useI18n();
const languages: { value: Locale; label: string }[] = [
    { value: 'en', label: 'English' }, { value: 'id', label: 'Bahasa Indonesia' }, { value: 'ar', label: 'العربية' },
];
const switching = ref(false);
const failed = ref(false);
function changeLanguage(event: Event) {
    const select = event.target as HTMLSelectElement;
    const language = languages.find(language => language.value === select.value)?.value;
    if (!language) return;
    switching.value = true; failed.value = false;
    router.visit(props.languageUrls?.[language] ?? `/${language}`, {
        onError: () => { failed.value = true; select.value = locale.value; },
        onFinish: () => { switching.value = false; },
    });
}
</script>

<template>
    <div class="public-cms min-h-svh bg-[#F7F6F1] text-[#17211C]" :lang="locale" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
        <a href="#public-content" class="skip-link">{{ t('skip') }}</a>
        <header class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 border-b border-[#164734]/20 px-6 py-7 sm:px-10">
            <a :href="`/${locale}`" class="flex min-h-11 items-center gap-4 text-[#164734]" :aria-label="t('publicHome.home')">
                <span class="text-2xl font-semibold tracking-tight" dir="ltr">ICHES</span>
                <span class="max-w-xs border-s border-[#B79A5B] ps-4 text-xs leading-relaxed">{{ eventName }}</span>
            </a>
            <label class="flex items-center gap-3 text-sm">
                <span>{{ t('language') }}</span>
                <select :value="locale" :disabled="switching" :aria-busy="switching" class="min-h-11 max-w-full rounded-none border border-[#164734]/40 bg-transparent px-3 py-2" @change="changeLanguage">
                    <option v-for="language in languages" :key="language.value" :value="language.value" :lang="language.value">{{ language.label }}</option>
                </select>
            </label>
            <p v-if="failed" role="alert" class="w-full text-sm">{{ t('failure') }}</p>
        </header>
        <main id="public-content" tabindex="-1" class="mx-auto max-w-7xl px-6 py-12 sm:px-10 sm:py-20">
            <slot />
        </main>
        <footer class="mx-auto flex max-w-7xl flex-wrap items-baseline justify-between gap-4 border-t border-[#164734]/20 px-6 py-8 text-sm text-[#46554B] sm:px-10">
            <p class="font-semibold text-[#164734]" dir="ltr">ICHES · UNISYA</p>
            <p>{{ t('publicHome.footer') }}</p>
        </footer>
    </div>
</template>

<style scoped>
.public-cms { font-family: 'Inter Variable', 'Noto Sans Arabic Variable', sans-serif; overflow-wrap: anywhere; }
.public-cms :deep(h1), .public-cms :deep(h2) { font-family: 'Sora Variable', 'Noto Sans Arabic Variable', sans-serif; }
.public-cms:dir(rtl) :deep(h1), .public-cms:dir(rtl) :deep(h2) { letter-spacing: normal; }
.public-cms :deep(a:focus-visible), .public-cms :deep(select:focus-visible), .public-cms :deep(main:focus-visible) { outline: 2px solid #164734; outline-offset: 5px; }
.skip-link { position: fixed; inset-inline-start: 1rem; top: -8rem; z-index: 50; padding: 1rem; background: #f7f6f1; }
.skip-link:focus { top: 1rem; }
</style>
