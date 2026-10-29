<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutDashboard } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
defineProps<{ collapsed?: boolean }>();
defineEmits<{ navigate: [] }>();
const { t } = useI18n();
</script>

<template>
    <div class="flex h-full flex-col gap-10">
        <div :class="collapsed ? 'text-center' : ''">
            <p class="font-brand text-xl font-semibold tracking-tight" dir="ltr">{{ collapsed ? 'I' : 'ICHES' }}<span class="text-gold">.</span></p>
            <p v-if="!collapsed" class="mt-1 text-xs tracking-widest text-white/70" dir="ltr">UNISYA · 2027</p>
        </div>
        <nav :aria-label="t('navigation')">
            <p v-if="!collapsed" class="mb-3 px-3 text-xs text-white/70">{{ t('workspace') }}</p>
            <Link href="/dashboard" aria-current="page" :aria-label="t('dashboard')" :title="collapsed ? t('dashboard') : undefined" class="flex min-h-11 items-center gap-3 rounded-lg bg-white/15 px-3 py-3 text-sm font-medium" :class="collapsed ? 'justify-center' : ''" @click="$emit('navigate')">
                <LayoutDashboard class="size-5 shrink-0" aria-hidden="true" /><span v-if="!collapsed">{{ t('dashboard') }}</span>
            </Link>
        </nav>
        <p v-if="!collapsed" class="mt-auto border-t border-white/20 pt-5 text-xs leading-relaxed text-white/70">{{ t('preparation') }}</p>
    </div>
</template>
