<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutDashboard, FileText } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import { cmsEligible, type DashboardProps } from '@/types';
defineProps<{ collapsed?: boolean }>();
defineEmits<{ navigate: [] }>();
const { t } = useI18n();
const page = usePage<DashboardProps>();
const cms = computed(() => page.url.startsWith('/backoffice/website/pages'));
const eligible = computed(() => cmsEligible(page.props.workspaces, page.props.activeWorkspace));
</script>

<template>
    <div class="flex h-full flex-col gap-10">
        <div :class="collapsed ? 'text-center' : ''">
            <p class="font-brand text-xl font-semibold tracking-tight" dir="ltr">{{ collapsed ? 'I' : 'ICHES' }}<span class="text-gold">.</span></p>
            <p v-if="!collapsed" class="mt-1 text-xs tracking-widest text-muted-foreground" dir="ltr">UNISYA · 2027</p>
        </div>
        <nav :aria-label="t('navigation')">
            <p v-if="!collapsed" class="mb-3 px-3 text-xs text-muted-foreground">{{ t('workspace') }}</p>
            <Link href="/dashboard" :aria-current="!cms ? 'page' : undefined" :aria-label="t('dashboard')" :title="collapsed ? t('dashboard') : undefined" class="flex min-h-11 items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium hover:bg-sidebar-border focus-visible:outline-sidebar-ring" :class="[collapsed ? 'justify-center' : '', !cms ? 'bg-sidebar-accent text-sidebar-accent-foreground' : '']" @click="$emit('navigate')">
                <LayoutDashboard class="size-5 shrink-0" aria-hidden="true" /><span v-if="!collapsed">{{ t('dashboard') }}</span>
            </Link>
            <Link v-if="eligible" href="/backoffice/website/pages" :aria-current="cms ? 'page' : undefined" :aria-label="t('cms.title')" :title="collapsed ? t('cms.title') : undefined" class="mt-1 flex min-h-11 items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium hover:bg-sidebar-border focus-visible:outline-sidebar-ring" :class="[collapsed ? 'justify-center' : '', cms ? 'bg-sidebar-accent text-sidebar-accent-foreground' : '']" @click="$emit('navigate')">
                <FileText class="size-5 shrink-0" aria-hidden="true" /><span v-if="!collapsed">{{ t('cms.title') }}</span>
            </Link>
        </nav>
        <p v-if="!collapsed" class="mt-auto border-t border-sidebar-border pt-5 text-xs leading-relaxed text-muted-foreground">{{ t('preparation') }}</p>
    </div>
</template>
