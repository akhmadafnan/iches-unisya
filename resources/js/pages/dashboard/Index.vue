<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { CircleCheck, Sprout } from '@lucide/vue';
import type { DashboardProps } from '../../types';
import { workspaceLabel } from '../../i18n';
import WorkspaceLayout from '../../layouts/WorkspaceLayout.vue';
import WorkspaceSwitcher from '../../components/navigation/WorkspaceSwitcher.vue';

const props = defineProps<DashboardProps>();
const { t } = useI18n();
const selected = computed(() => props.workspaces.find(choice => choice.key === props.activeWorkspace));
</script>

<template>
    <Head :title="t('dashboard')" />
    <WorkspaceLayout>
        <div class="mb-10 flex flex-wrap items-start justify-between gap-4">
            <div class="max-w-2xl"><p class="mb-3 text-xs font-semibold uppercase tracking-widest text-primary">{{ t('overview') }}</p><h1 class="font-brand text-3xl leading-snug font-semibold tracking-tight sm:text-4xl">{{ t('welcome', { name: account.name }) }}</h1><p class="mt-4 leading-relaxed text-muted-foreground">{{ t('introduction') }}</p></div>
            <span class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-3 py-2 text-xs font-medium text-primary"><CircleCheck class="size-4" aria-hidden="true" />{{ t('ready') }}</span>
        </div>
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
            <section class="rounded-2xl border border-border bg-card p-6 sm:p-8" aria-labelledby="context-title">
                <p class="text-xs text-muted-foreground">{{ t('current') }}</p><h2 id="context-title" class="mt-3 font-brand text-xl leading-relaxed font-semibold break-words" role="status">{{ selected ? workspaceLabel(selected) : t('personal') }}</h2>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-muted-foreground">{{ selected?.role ? t('contextHelp') : t('personalHelp') }}</p>
                <dl class="mt-8 grid gap-6 border-t border-border pt-6 text-sm sm:grid-cols-2">
                    <div><dt class="text-muted-foreground">{{ t('identity') }}</dt><dd class="mt-2 font-medium break-words">{{ account.name }}<span class="mt-1 block break-all text-xs font-normal text-muted-foreground" dir="ltr">{{ account.email }}</span></dd></div>
                    <div v-if="selected?.role"><dt class="text-muted-foreground">{{ t('role') }}</dt><dd class="mt-2 font-medium">{{ t(`roles.${selected.role}`) }}</dd></div>
                    <div v-if="selected?.event"><dt class="text-muted-foreground">{{ t('event') }}</dt><dd class="mt-2 break-words">{{ selected.event }}</dd></div>
                    <div v-if="selected?.institution"><dt class="text-muted-foreground">{{ t('institution') }}</dt><dd class="mt-2 break-words">{{ selected.institution }}</dd></div>
                </dl>
            </section>
            <section class="rounded-2xl border border-border bg-card p-6 sm:p-8" aria-labelledby="workspace-title"><h2 id="workspace-title" class="mb-6 font-brand text-lg font-semibold">{{ t('workspace') }}</h2><WorkspaceSwitcher /></section>
        </div>
        <section class="mt-6 flex gap-4 rounded-2xl border border-border border-s-4 border-s-gold p-6 sm:p-8" aria-labelledby="preparation-title">
            <Sprout class="mt-1 size-6 shrink-0 text-primary" aria-hidden="true" /><div><h2 id="preparation-title" class="font-brand font-semibold">{{ t('preparation') }}</h2><p class="mt-2 max-w-3xl text-sm leading-relaxed text-muted-foreground">{{ t('preparationBody') }}</p></div>
        </section>
    </WorkspaceLayout>
</template>
