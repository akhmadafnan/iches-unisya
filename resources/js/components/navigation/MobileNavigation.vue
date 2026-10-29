<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { DialogRoot, DialogTrigger, DialogClose, DialogTitle, DialogDescription } from 'reka-ui';
import { Menu, X } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import AppSidebar from './AppSidebar.vue';
import Button from '../ui/Button.vue';
import SheetContent from '../ui/SheetContent.vue';

const { t } = useI18n();
const open = ref(false);
let media: MediaQueryList | undefined;
function closeOnDesktop() { if (media?.matches) open.value = false; }
onMounted(() => { media = matchMedia('(min-width: 1024px)'); media.addEventListener('change', closeOnDesktop); });
onUnmounted(() => media?.removeEventListener('change', closeOnDesktop));
</script>

<template>
    <DialogRoot v-model:open="open">
        <DialogTrigger as-child><Button variant="outline" class="lg:hidden" :aria-label="t('openNavigation')"><Menu aria-hidden="true" /></Button></DialogTrigger>
        <SheetContent class="border-sidebar-border bg-sidebar text-sidebar-foreground">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div><DialogTitle class="font-brand font-semibold">{{ t('navigation') }}</DialogTitle><DialogDescription class="mt-1 text-xs text-muted-foreground">ICHES UNISYA 2027</DialogDescription></div>
                <DialogClose as-child><Button variant="ghost" :aria-label="t('closeNavigation')"><X aria-hidden="true" /></Button></DialogClose>
            </div>
            <div class="min-h-80 flex-1"><AppSidebar @navigate="open = false" /></div>
        </SheetContent>
    </DialogRoot>
</template>
