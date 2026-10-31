<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';
import { calculateCountdown, parseCountdownTarget } from '@/lib/public-cms/countdown';
import type { SectionOf } from '@/lib/public-cms/types';

const props = defineProps<{ section: SectionOf<'countdown'> }>();
const { t, locale } = useI18n();
const remaining = ref<ReturnType<typeof calculateCountdown>>(null);
const units = ['days', 'hours', 'minutes', 'seconds'] as const;
let timer: ReturnType<typeof setInterval> | undefined;
let mounted = false;
function stop() { clearInterval(timer); timer = undefined; }
function update() {
    remaining.value = calculateCountdown(props.section.bindings.starts_at, Date.now());
    if (!remaining.value || remaining.value.started) stop();
}
function resume() {
    stop();
    update();
    if (!document.hidden && remaining.value && !remaining.value.started) timer = setInterval(update, 1000);
}
watch(() => props.section.bindings.starts_at, () => { if (mounted) resume(); });
onMounted(() => { mounted = true; resume(); document.addEventListener('visibilitychange', resume); });
onBeforeUnmount(() => { stop(); document.removeEventListener('visibilitychange', resume); });
</script>

<template>
    <h2 class="text-2xl leading-snug font-semibold text-[#164734]">{{ section.content.heading }}</h2>
    <p class="mt-5 leading-relaxed" role="status" :lang="locale" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
        {{ parseCountdownTarget(section.bindings.starts_at) === null ? t('publicCms.timeUnconfirmed') : remaining?.started ? t('publicCms.eventStarted') : t('publicCms.countingDown') }}
    </p>
    <dl v-if="remaining && !remaining.started" dir="ltr" aria-live="off" class="mt-6 grid grid-cols-2 gap-x-4 gap-y-6 sm:grid-cols-4">
        <div v-for="unit in units" :key="unit" class="min-w-0 text-center">
            <dt :dir="locale === 'ar' ? 'rtl' : 'ltr'" class="text-sm text-[#164734]">{{ t(`publicCms.${unit}`) }}</dt>
            <dd class="mt-2 text-3xl font-semibold tabular-nums sm:text-4xl">{{ String(remaining[unit]).padStart(2, '0') }}</dd>
        </div>
    </dl>
</template>
