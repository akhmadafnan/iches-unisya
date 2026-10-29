<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue';
import { ConfigProvider, DialogRoot, DialogTrigger, DialogTitle, DialogDescription, DialogClose } from 'reka-ui';
import Button from '@/components/ui/Button.vue';
import Input from '@/components/ui/Input.vue';
import Textarea from '@/components/ui/Textarea.vue';
import Label from '@/components/ui/Label.vue';
import Badge from '@/components/ui/Badge.vue';
import NativeSelect from '@/components/ui/NativeSelect.vue';
import DialogContent from '@/components/ui/DialogContent.vue';
import Table from '@/components/ui/Table.vue';

const locale = ref<'en' | 'id' | 'ar'>('en');
const direction = computed(() => locale.value === 'ar' ? 'rtl' : 'ltr');
const words = computed(() => ({
    en: { title: 'Local UI primitives fixture', name: 'Name', notes: 'Notes', choice: 'Choice', badge: 'Fixture sample' },
    id: { title: 'Fixture lokal komponen UI', name: 'Nama', notes: 'Catatan', choice: 'Pilihan', badge: 'Contoh fixture' },
    ar: { title: 'اختبار مكونات الواجهة المحلي', name: 'الاسم', notes: 'ملاحظات', choice: 'اختيار', badge: 'عينة اختبار' },
})[locale.value]);
const state = reactive({ name: 'Fixture', notes: 'Fixture notes', choice: 'a', number: 7, multiple: ['a'], inputs: 0, blurs: 0, escapes: 0, opens: 0 });
const longDialog = ref(false);
const open = ref(false);
watch(locale, () => { document.documentElement.lang = locale.value; document.documentElement.dir = direction.value; }, { immediate: true });
defineExpose({ state, locale, longDialog });
</script>

<template>
    <ConfigProvider :dir="direction">
        <main class="iches-backoffice mx-auto grid min-h-svh w-full max-w-3xl min-w-0 gap-6 bg-background p-4 text-foreground">
            <h1 class="font-heading text-xl">{{ words.title }}</h1>
            <p>ICH-UI-01C — isolated test fixture, not a CMS page.</p>
            <form class="grid min-w-0 gap-3" @submit.prevent>
                <Label for="name">{{ words.name }}</Label>
                <Input id="name" v-model="state.name" name="fixture-name" required maxlength="80" autocomplete="off" aria-describedby="name-help" data-fixture="input" @input="state.inputs++" @blur="state.blurs++" />
                <p id="name-help" class="text-xs text-muted-foreground">Fixture helper text</p>
                <Label for="notes">{{ words.notes }}</Label>
                <Textarea id="notes" v-model="state.notes" name="fixture-notes" rows="3" required maxlength="500" />
                <Label for="choice">{{ words.choice }}</Label>
                <NativeSelect id="choice" v-model="state.choice" name="fixture-choice" required>
                    <optgroup label="Fixture group"><option value="a">Option A</option><option value="b">Option B</option><option value="c" disabled>Disabled option</option></optgroup>
                </NativeSelect>
                <Label for="number">Number fixture</Label><Input id="number" v-model="state.number" type="number" min="0" max="99" />
                <Label for="multiple">Multiple fixture</Label><NativeSelect id="multiple" v-model="state.multiple" multiple class="h-16"><option value="a">A</option><option value="b">B</option></NativeSelect>
                <Label for="invalid">Invalid fixture</Label><Input id="invalid" aria-invalid="true" aria-describedby="invalid-help" /><p id="invalid-help" class="text-xs">Fixture invalid state sample</p>
                <Input id="disabled-input" disabled aria-label="Disabled input fixture" />
                <Textarea id="disabled-textarea" disabled aria-label="Disabled textarea fixture" />
                <NativeSelect id="disabled-select" disabled aria-label="Disabled select fixture"><option>Disabled</option></NativeSelect>
                <Input id="readonly" readonly model-value="Read only" aria-label="Readonly fixture" />
            </form>
            <div class="flex flex-wrap gap-2"><Badge v-for="variant in (['default','secondary','destructive','outline'] as const)" :key="variant" :variant="variant">{{ words.badge }}: {{ variant }}</Badge></div>
            <div class="flex flex-wrap gap-2">
                <Button v-for="variant in (['default','outline','ghost'] as const)" :key="variant" :variant="variant" :data-variant="variant" type="button">{{ variant }}</Button>
                <Button id="disabled-button" disabled>Disabled</Button>
                <Button id="button-as" as="a" href="#fixture-end">as link</Button>
                <Button as-child><a id="button-child" href="#fixture-end">asChild link</a></Button>
            </div>
            <DialogRoot v-model:open="open">
                <DialogTrigger as-child><Button id="open-dialog">Open fixture dialog</Button></DialogTrigger>
                <DialogContent id="fixture-dialog" data-fixture="dialog" @escape-key-down="state.escapes++" @open-auto-focus="state.opens++">
                    <DialogTitle class="font-heading text-lg">{{ words.title }}</DialogTitle>
                    <DialogDescription>Dialog focus and scrolling test fixture.</DialogDescription>
                    <Label for="dialog-input">{{ words.name }}</Label><Input id="dialog-input" />
                    <p v-for="line in (longDialog ? 50 : 1)" :key="line">{{ words.notes }} — fixture line {{ line }}</p>
                    <DialogClose as-child><Button id="close-dialog" variant="outline">Close fixture dialog</Button></DialogClose>
                </DialogContent>
            </DialogRoot>
            <Table id="fixture-table" aria-describedby="table-help" class="min-w-[48rem]">
                <caption class="p-2">Fixture table — synthetic test cells only</caption>
                <thead><tr class="border-b border-border"><th v-for="column in 8" :key="column" scope="col" class="px-2 py-2 text-start">Column {{ column }}</th></tr></thead>
                <tbody><tr v-for="row in 3" :key="row" class="border-b border-border"><td v-for="column in 8" :key="column" class="px-2 py-2">Fixture {{ row }}:{{ column }}</td></tr></tbody>
            </Table>
            <p id="table-help" class="text-xs">Wide table overflow fixture.</p>
            <output id="model-state" class="block break-all text-xs">{{ JSON.stringify(state) }}</output>
            <a id="fixture-end" href="#name">End of fixture</a>
        </main>
    </ConfigProvider>
</template>
