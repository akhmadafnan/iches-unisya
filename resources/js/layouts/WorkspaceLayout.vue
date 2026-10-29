<script setup lang="ts">
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { ConfigProvider } from 'reka-ui';
import { PanelLeftClose, PanelLeftOpen } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import type { DashboardProps } from '../types';
import AppSidebar from '../components/navigation/AppSidebar.vue';
import AppTopbar from '../components/navigation/AppTopbar.vue';
import Button from '../components/ui/Button.vue';

const page = usePage<DashboardProps>();
const { t } = useI18n();
const collapsed = ref(false);
</script>

<template>
    <ConfigProvider :dir="page.props.direction">
        <div class="flex min-h-svh bg-background text-foreground">
            <a href="#main-content" class="skip-link">{{ t('skip') }}</a>
            <aside class="sticky top-0 hidden h-svh shrink-0 flex-col gap-5 bg-primary px-4 py-7 text-primary-foreground lg:flex" :class="collapsed ? 'w-20' : 'w-64'">
                <AppSidebar :collapsed="collapsed" />
                <Button variant="ghost" class="w-full" :aria-label="collapsed ? t('expand') : t('collapse')" :aria-expanded="!collapsed" @click="collapsed = !collapsed"><component :is="collapsed ? PanelLeftOpen : PanelLeftClose" class="rtl:rotate-180" aria-hidden="true" /><span v-if="!collapsed">{{ t('collapse') }}</span></Button>
            </aside>
            <div class="min-w-0 flex-1">
                <AppTopbar />
                <main id="main-content" tabindex="-1" class="mx-auto w-full max-w-7xl px-4 py-9 sm:px-8 sm:py-12"><slot /></main>
            </div>
        </div>
    </ConfigProvider>
</template>
