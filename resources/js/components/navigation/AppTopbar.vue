<script setup lang="ts">
import { computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { DropdownMenuRoot, DropdownMenuTrigger, DropdownMenuPortal, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from 'reka-ui';
import { ChevronDown, ChevronRight, LogOut, UserRound } from '@lucide/vue';
import type { DashboardProps } from '../../types';
import { workspaceLabel } from '../../i18n';
import Button from '../ui/Button.vue';
import LocaleSwitcher from './LocaleSwitcher.vue';
import MobileNavigation from './MobileNavigation.vue';

const page = usePage<DashboardProps>();
const { t } = useI18n();
const logout = useForm({});
const selected = computed(() => page.props.workspaces.find(choice => choice.key === page.props.activeWorkspace));
</script>

<template>
    <header class="border-b border-border bg-card px-4 py-4 sm:px-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-3">
                <MobileNavigation />
                <div class="min-w-0"><p class="font-brand text-sm font-semibold" dir="ltr">ICHES UNISYA 2027</p><p class="mt-1 max-w-xs truncate text-xs text-muted-foreground">{{ selected ? workspaceLabel(selected) : t('personal') }}</p></div>
            </div>
            <div class="flex flex-wrap items-end gap-3">
                <LocaleSwitcher />
                <DropdownMenuRoot :dir="page.props.direction">
                    <DropdownMenuTrigger as-child><Button variant="outline" :aria-label="t('account')"><UserRound aria-hidden="true" /><ChevronDown aria-hidden="true" /></Button></DropdownMenuTrigger>
                    <DropdownMenuPortal>
                        <DropdownMenuContent align="end" :side-offset="8" class="z-50 w-64 max-w-[90vw] rounded-xl border border-border bg-card p-2 text-foreground shadow-lg">
                            <DropdownMenuLabel class="px-3 py-2"><span class="block break-words text-sm font-semibold">{{ page.props.account.name }}</span><span class="mt-1 block break-all text-xs font-normal text-muted-foreground" dir="ltr">{{ page.props.account.email }}</span></DropdownMenuLabel>
                            <DropdownMenuSeparator class="my-1 h-px bg-border" />
                            <DropdownMenuItem class="flex min-h-11 cursor-pointer items-center gap-2 rounded-md px-3 text-sm outline-none data-highlighted:bg-muted" :disabled="logout.processing" @select="logout.post('/logout')"><LogOut class="size-4" aria-hidden="true" />{{ t('signOut') }}</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenuPortal>
                </DropdownMenuRoot>
            </div>
        </div>
        <nav class="mt-5 text-xs text-muted-foreground" :aria-label="t('navigation')">
            <ol class="flex flex-wrap items-center gap-2"><li>{{ t('workspace') }}</li><li><ChevronRight class="size-3 rtl:rotate-180" aria-hidden="true" /></li><li aria-current="page" class="font-medium text-foreground">{{ t('dashboard') }}</li></ol>
        </nav>
    </header>
</template>
