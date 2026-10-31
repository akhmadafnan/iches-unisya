<script setup lang="ts">
import { onBeforeUnmount, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { DialogRoot, DialogTitle, DialogDescription, DialogClose, DialogTrigger } from 'reka-ui';
import { useI18n } from 'vue-i18n';
import type { PagesProps } from '@/lib/cms/schema';
import { CmsApiError, cmsErrorKey, cmsRequest } from '@/lib/cms/api';
import WorkspaceLayout from '@/layouts/WorkspaceLayout.vue';
import WorkspaceSwitcher from '@/components/navigation/WorkspaceSwitcher.vue';
import Button from '@/components/ui/Button.vue';
import NativeSelect from '@/components/ui/NativeSelect.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Table from '@/components/ui/Table.vue';
import Badge from '@/components/ui/Badge.vue';
import DialogContent from '@/components/ui/DialogContent.vue';

const props = defineProps<PagesProps>();
const { t } = useI18n();
const search = ref(props.search);
const localStart = ref(props.eventSchedule.local_start ?? '');
const timezone = ref(props.eventSchedule.timezone);
const currentStart = ref(props.eventSchedule.starts_at);
const currentTimezone = ref(props.eventSchedule.timezone);
const scheduleOpen = ref(false);
const scheduleBusy = ref(false);
const scheduleError = ref('');
const scheduleFields = ref<Record<string, string[]>>({});
const scheduleSaved = ref(false);
watch(() => props.eventSchedule, (schedule) => {
    localStart.value = schedule.local_start ?? '';
    timezone.value = currentTimezone.value = schedule.timezone;
    currentStart.value = schedule.starts_at;
    scheduleSaved.value = false;
    scheduleError.value = '';
    scheduleFields.value = {};
    scheduleOpen.value = false;
});
async function saveSchedule() {
    if (scheduleBusy.value) return;
    const eventId = props.cmsEvent.id;
    const workspace = props.activeWorkspace;
    const currentRequest = () => !disposed && props.cmsEvent.id === eventId && props.activeWorkspace === workspace;
    scheduleBusy.value = true; scheduleError.value = ''; scheduleFields.value = {}; scheduleSaved.value = false;
    try {
        const result = await cmsRequest<{ starts_at: string; timezone: string }>(`/workspace/events/${eventId}/schedule`, {
            local_start: localStart.value, timezone: timezone.value, confirmed: true,
        });
        if (currentRequest()) {
            currentStart.value = result.starts_at; currentTimezone.value = result.timezone;
            scheduleSaved.value = true; scheduleOpen.value = false;
        }
    } catch (failure) {
        if (currentRequest()) {
            scheduleError.value = cmsErrorKey(failure);
            if (failure instanceof CmsApiError) scheduleFields.value = failure.fields;
            scheduleOpen.value = false;
        }
    } finally { if (!disposed) scheduleBusy.value = false; }
}
const slug = ref('');
const open = ref(false);
const busy = ref(false);
const error = ref('');
const fields = ref<Record<string, string[]>>({});
let disposed = false;
onBeforeUnmount(() => { disposed = true; });
function filter() {
    busy.value = true;
    router.get('/backoffice/website/pages', { search: search.value }, { preserveState: true, replace: true, onFinish: () => { busy.value = false; } });
}
async function create() {
    if (busy.value) return;
    busy.value = true; error.value = ''; fields.value = {};
    try {
        const result = await cmsRequest<{ page_id: string }>(`/workspace/events/${props.cmsEvent.id}/cms/pages`, { slug: slug.value });
        if (!disposed) router.visit(`/backoffice/website/pages/${result.page_id}`);
    } catch (failure) {
        if (!disposed) { error.value = cmsErrorKey(failure); if (failure instanceof CmsApiError) fields.value = failure.fields; }
    } finally { if (!disposed) busy.value = false; }
}
</script>

<template>
    <Head :title="t('cms.title')"><meta name="robots" content="noindex, nofollow, noarchive" /></Head>
    <WorkspaceLayout>
        <div class="mb-6 flex flex-wrap items-start justify-between gap-4"><div><h1 class="font-brand text-2xl font-semibold">{{ t('cms.title') }}</h1><p class="mt-2 text-sm text-muted-foreground">{{ cmsEvent.name }} · {{ t('cms.pagesDescription') }}</p></div><WorkspaceSwitcher /></div>
        <section class="mb-6 min-w-0 border border-border bg-card p-4 sm:p-6" :aria-busy="scheduleBusy" aria-labelledby="schedule-title">
            <h2 id="schedule-title" class="font-brand font-semibold">{{ t('schedule.title') }}</h2>
            <p class="mt-2 text-sm text-muted-foreground">{{ t('schedule.help') }}</p>
            <p class="mt-3 break-words text-sm">{{ t('schedule.current') }}: <time v-if="currentStart" :datetime="currentStart" dir="ltr">{{ currentStart }}</time><span v-else>{{ t('publicCms.timeUnconfirmed') }}</span> &middot; <bdi>{{ currentTimezone }}</bdi></p>
            <form class="mt-5 grid min-w-0 gap-4 sm:grid-cols-2" @submit.prevent="scheduleOpen = true">
                <div class="min-w-0"><Label for="schedule-start">{{ t('schedule.localStart') }}</Label><Input id="schedule-start" v-model="localStart" type="datetime-local" step="60" required dir="ltr" :disabled="scheduleBusy" :aria-invalid="!!scheduleFields.local_start" aria-describedby="schedule-start-error" /><p id="schedule-start-error" class="mt-2 text-xs text-destructive">{{ scheduleFields.local_start ? t(scheduleFields.local_start[0] === 'schedule.beforeEnd' ? 'schedule.beforeEnd' : 'schedule.invalidTime') : '' }}</p></div>
                <div class="min-w-0"><Label for="schedule-zone">{{ t('schedule.timezone') }}</Label><NativeSelect id="schedule-zone" v-model="timezone" required dir="ltr" :disabled="scheduleBusy" :aria-invalid="!!scheduleFields.timezone" aria-describedby="schedule-zone-error"><option v-for="zone in eventSchedule.timezones" :key="zone" :value="zone">{{ zone }}</option></NativeSelect><p id="schedule-zone-error" class="mt-2 text-xs text-destructive">{{ scheduleFields.timezone ? t('schedule.invalidTimezone') : '' }}</p></div>
                <p v-if="scheduleError" role="alert" class="text-sm text-destructive sm:col-span-2">{{ t(scheduleError) }}</p>
                <p v-if="scheduleSaved" role="status" class="text-sm sm:col-span-2">{{ t('schedule.saved') }}</p>
                <div><Button type="submit" :disabled="scheduleBusy">{{ scheduleBusy ? t('saving') : t('schedule.save') }}</Button></div>
            </form>
            <DialogRoot v-model:open="scheduleOpen" :dir="direction"><DialogContent @interact-outside="scheduleBusy && $event.preventDefault()" @escape-key-down="scheduleBusy && $event.preventDefault()">
                <DialogTitle class="font-brand text-lg font-semibold">{{ t('schedule.confirm') }}</DialogTitle>
                <DialogDescription class="mt-2 text-sm text-muted-foreground">{{ t('schedule.confirmHelp') }} <bdi>{{ localStart }} &middot; {{ timezone }}</bdi></DialogDescription>
                <div class="mt-4 flex flex-wrap gap-2"><Button :disabled="scheduleBusy" @click="saveSchedule">{{ scheduleBusy ? t('saving') : t('cms.confirm') }}</Button><DialogClose as-child><Button variant="outline" :disabled="scheduleBusy">{{ t('cms.cancel') }}</Button></DialogClose></div>
            </DialogContent></DialogRoot>
        </section>
        <div v-if="!schemaAvailable" role="status" class="border border-border bg-card p-6"><h2 class="font-brand font-semibold">{{ t('cms.schemaTitle') }}</h2><p class="mt-3 max-w-2xl text-sm text-muted-foreground">{{ t('cms.schemaBody') }}</p></div>
        <section v-else class="min-w-0 border border-border bg-card p-4 sm:p-6" :aria-busy="busy">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <form class="flex min-w-0 flex-wrap items-end gap-2" @submit.prevent="filter"><div><Label for="cms-search">{{ t('cms.search') }}</Label><Input id="cms-search" v-model="search" type="search" maxlength="120" :disabled="busy" /></div><Button type="submit" variant="outline" :disabled="busy">{{ t('cms.searchAction') }}</Button></form>
                <DialogRoot v-model:open="open" :dir="direction"><DialogTrigger as-child><Button :disabled="busy">{{ t('cms.create') }}</Button></DialogTrigger><DialogContent><DialogTitle class="font-brand text-lg font-semibold">{{ t('cms.create') }}</DialogTitle><DialogDescription class="text-muted-foreground">{{ t('cms.slugHelp') }}</DialogDescription><form class="space-y-4" @submit.prevent="create"><div><Label for="cms-slug">{{ t('cms.slug') }}</Label><Input id="cms-slug" v-model="slug" dir="ltr" maxlength="120" required pattern="[a-z0-9]+(-[a-z0-9]+)*" :disabled="busy" :aria-invalid="!!fields.slug" aria-describedby="cms-slug-error" /><p id="cms-slug-error" class="mt-2 text-xs text-destructive">{{ fields.slug?.join(' ') }}</p></div><p v-if="error" role="alert" class="text-sm text-destructive">{{ t(error) }}</p><div class="flex flex-wrap gap-2"><Button type="submit" :disabled="busy">{{ busy ? t('saving') : t('cms.create') }}</Button><DialogClose as-child><Button type="button" variant="outline" :disabled="busy">{{ t('cms.cancel') }}</Button></DialogClose></div></form></DialogContent></DialogRoot>
            </div>
            <p v-if="busy" role="status" class="mb-3 text-xs text-muted-foreground">{{ t('cms.loading') }}</p>
            <Table v-if="pages?.data.length"><caption class="sr-only">{{ t('cms.title') }}</caption><thead><tr class="border-b border-border text-start"><th scope="col" class="p-3 text-start">{{ t('cms.slug') }}</th><th scope="col" class="p-3 text-start">{{ t('cms.state') }}</th><th scope="col" class="p-3 text-start">{{ t('cms.updated') }}</th><th scope="col" class="p-3 text-start">{{ t('cms.editor') }}</th></tr></thead><tbody><tr v-for="page in pages.data" :key="page.id" class="border-b border-border"><td class="p-3"><Link :href="`/backoffice/website/pages/${page.id}`" class="font-medium underline underline-offset-4" dir="ltr">{{ page.slug }}</Link></td><td class="p-3"><div class="flex flex-wrap gap-2"><Badge v-if="page.published_version">{{ t('cms.published') }} · {{ page.published_version }}</Badge><Badge v-if="page.draft_version" variant="secondary">{{ t('cms.draft') }} · {{ page.draft_version }}</Badge><Badge v-if="!page.draft_version && !page.published_version" variant="outline">{{ t('cms.empty') }}</Badge></div></td><td class="p-3"><time :datetime="page.updated_at ?? undefined">{{ page.updated_at }}</time></td><td class="p-3"><Link :href="`/backoffice/website/pages/${page.id}`" class="underline underline-offset-4">{{ t('cms.edit') }}</Link></td></tr></tbody></Table>
            <div v-else class="py-12 text-center"><h2 class="font-brand font-semibold">{{ t(search ? 'cms.noMatches' : 'cms.noPages') }}</h2><p class="mt-3 text-sm text-muted-foreground">{{ t('cms.emptyHelp') }}</p></div>
            <nav v-if="pages && pages.last_page > 1" class="mt-6 flex flex-wrap items-center justify-between gap-3" :aria-label="t('cms.pagination')"><Link v-if="pages.prev_page_url" :href="pages.prev_page_url" class="min-h-11 p-3 underline">{{ t('cms.previous') }}</Link><span class="text-xs">{{ t('cms.pageNumber', { current: pages.current_page, total: pages.last_page }) }}</span><Link v-if="pages.next_page_url" :href="pages.next_page_url" class="min-h-11 p-3 underline">{{ t('cms.next') }}</Link></nav>
        </section>
    </WorkspaceLayout>
</template>
