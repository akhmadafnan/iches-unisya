<script setup lang="ts">
import { computed, nextTick, onMounted, onBeforeUnmount, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { DialogRoot, DialogTitle, DialogDescription, DialogClose } from 'reka-ui';
import { useI18n } from 'vue-i18n';
import type { Locale } from '@/types';
import type { EditorProps, Revision, RevisionResponse, Snapshot, TranslationStatus, MediaPage, MediaItem } from '@/lib/cms/schema';
import { addSection, canPublish, changeSection, editableSnapshot, emptySnapshot, locales, setStatus, statuses, validField } from '@/lib/cms/editor';
import { CmsApiError, cmsErrorKey, cmsRequest } from '@/lib/cms/api';
import WorkspaceLayout from '@/layouts/WorkspaceLayout.vue';
import SectionEditor from '@/components/cms/SectionEditor.vue';
import RevisionHistory from '@/components/cms/RevisionHistory.vue';
import Button from '@/components/ui/Button.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import NativeSelect from '@/components/ui/NativeSelect.vue';
import Badge from '@/components/ui/Badge.vue';
import DialogContent from '@/components/ui/DialogContent.vue';

const props = defineProps<EditorProps>();
const { t } = useI18n();
const snapshot = ref<Snapshot>(editableSnapshot(props.snapshot ?? emptySnapshot()));
const mediaPage = ref<MediaPage>({ data: [], next_page_url: null, prev_page_url: null });
const upload = ref<HTMLInputElement | null>(null);
const mediaBusy = ref(false);
const mediaError = ref('');
const mediaNotice = ref(false);
let mediaRequest = 0;
const mediaBase = () => `/workspace/events/${props.cmsEvent.id}/cms/media`;
async function loadMedia(page = 1) {
    const request = ++mediaRequest;
    mediaBusy.value = true; mediaError.value = '';
    try {
        const result = await cmsRequest<MediaPage>(`${mediaBase()}?page=${page}`);
        if (!disposed && request === mediaRequest) mediaPage.value = result;
    } catch (failure) {
        if (!disposed && request === mediaRequest) mediaError.value = cmsErrorKey(failure);
    } finally { if (!disposed && request === mediaRequest) mediaBusy.value = false; }
}
function mediaPageNumber(url: string) { return Number(new URL(url, window.location.origin).searchParams.get('page') ?? 1); }
async function uploadMedia() {
    const file = upload.value?.files?.[0];
    if (!file || mediaBusy.value) return;
    const request = ++mediaRequest;
    mediaBusy.value = true; mediaError.value = ''; mediaNotice.value = false;
    try {
        const body = new FormData(); body.append('file', file);
        const token = document.cookie.split('; ').find(cookie => cookie.startsWith('XSRF-TOKEN='))?.slice(11);
        const response = await fetch(mediaBase(), {
            method: 'POST', credentials: 'same-origin', cache: 'no-store', redirect: 'error', body,
            headers: { Accept: 'application/json', ...(token ? { 'X-XSRF-TOKEN': decodeURIComponent(token) } : {}) },
        });
        if (!response.ok) throw new CmsApiError(response.status);
        const item = await response.json() as MediaItem;
        if (!disposed && request === mediaRequest) {
            mediaPage.value.data = [item, ...mediaPage.value.data.filter(value => value.id !== item.id)];
            mediaNotice.value = true;
            if (upload.value) upload.value.value = '';
        }
    } catch (failure) {
        if (!disposed && request === mediaRequest) mediaError.value = failure instanceof CmsApiError && failure.status === 422 ? 'media.invalidUpload' : cmsErrorKey(failure);
    } finally { if (!disposed && request === mediaRequest) mediaBusy.value = false; }
}
onMounted(() => { void loadMedia(); });
watch(() => [props.cmsPage.id, props.activeWorkspace], () => {
    mediaPage.value = { data: [], next_page_url: null, prev_page_url: null };
    mediaNotice.value = false;
    if (upload.value) upload.value.value = '';
    void loadMedia();
});
const saved = ref(JSON.stringify(snapshot.value));
const draftId = ref(props.cmsPage.draft_revision_id);
const locale = ref<Locale>(props.locale);
const componentKey = ref(Object.keys(props.catalog)[0] ?? '');
const busy = ref(false);
const error = ref('');
const fields = ref<Record<string, string[]>>({});
const notice = ref('');
const preview = ref<Snapshot | null>(null);
const previewOpen = ref(false);
const confirmation = ref(false);
const restoreTarget = ref<Revision | null>(null);
const dirty = computed(() => JSON.stringify(snapshot.value) !== saved.value);
const publishable = computed(() => !dirty.value && !!draftId.value && canPublish(snapshot.value) && draftId.value !== props.cmsPage.published_revision_id);
const api = computed(() => `/workspace/events/${props.cmsEvent.id}/cms/pages/${props.cmsPage.id}`);
let disposed = false;
watch(() => [props.cmsPage.id, props.activeWorkspace, props.snapshot] as const, () => {
    snapshot.value = editableSnapshot(props.snapshot ?? emptySnapshot()); saved.value = JSON.stringify(snapshot.value);
    draftId.value = props.cmsPage.draft_revision_id; preview.value = null; previewOpen.value = false;
});
const removeBefore = router.on('before', event => {
    if (dirty.value && !busy.value && !window.confirm(t('cms.leaveUnsaved'))) event.preventDefault();
});
function beforeUnload(event: BeforeUnloadEvent) { if (dirty.value) event.preventDefault(); }
window.addEventListener('beforeunload', beforeUnload);
onBeforeUnmount(() => { disposed = true; mediaRequest++; removeBefore(); window.removeEventListener('beforeunload', beforeUnload); });
function refresh(): Promise<void> {
    return new Promise((resolve, reject) => {
        let completed = false;
        router.reload({
            onSuccess: () => { completed = true; resolve(); }, onError: () => { reject(new CmsApiError(503)); },
            onCancel: () => { reject(new CmsApiError(409)); },
            onFinish: () => { if (!completed) reject(new CmsApiError(503)); },
        });
    });
}
function reloadState() {
    if (dirty.value && !window.confirm(t('cms.leaveUnsaved'))) return;
    void run(refresh);
}
async function run(action: () => Promise<void>) {
    if (busy.value) return;
    busy.value = true; error.value = ''; fields.value = {}; notice.value = '';
    try { await action(); }
    catch (failure) {
        if (!disposed) { error.value = cmsErrorKey(failure); if (failure instanceof CmsApiError) fields.value = failure.fields; }
    } finally { if (!disposed) busy.value = false; }
}
async function save() {
    const result = await cmsRequest<RevisionResponse>(`${api.value}/draft`, { snapshot: snapshot.value });
    if (disposed) return;
    draftId.value = result.revision_id; saved.value = JSON.stringify(snapshot.value); notice.value = 'cms.saved';
}
function saveDraft() { void run(async () => { await save(); if (!disposed) await refresh(); }); }
function showPreview() { void run(async () => {
    if (dirty.value || !draftId.value) { await save(); if (!disposed) await refresh(); }
    if (disposed) return;
    const result = await cmsRequest<RevisionResponse>(`${api.value}/preview`);
    if (!disposed && result.snapshot) { preview.value = result.snapshot; previewOpen.value = true; }
}); }
function publishOrRestore() { void run(async () => {
    const restoring = restoreTarget.value;
    await cmsRequest<RevisionResponse>(`${api.value}/${restoring ? 'restore' : 'publish'}`, { revision_id: restoring?.id ?? draftId.value });
    if (disposed) return;
    confirmation.value = false; notice.value = restoring ? 'cms.restored' : 'cms.publishedSuccess'; await refresh();
}); }
function confirmRestore(revision: Revision) { restoreTarget.value = revision; confirmation.value = true; }
async function sectionAction(index: number, action: 'up' | 'down' | 'duplicate' | 'delete') {
    snapshot.value = changeSection(snapshot.value, index, action);
    await nextTick();
    const position = action === 'up' ? index - 1 : action === 'down' || action === 'duplicate' ? index + 1 : Math.min(index, snapshot.value.sections.length - 1);
    document.getElementById(`${snapshot.value.sections[position]?.id}-heading`)?.focus();
}
</script>

<template>
    <Head :title="`${cmsPage.slug} · ${t('cms.editor')}`"><meta name="robots" content="noindex, nofollow, noarchive" /></Head>
    <WorkspaceLayout>
        <div class="mb-6 flex flex-wrap items-start justify-between gap-4"><div><Link href="/backoffice/website/pages" class="text-xs underline underline-offset-4">{{ t('cms.back') }}</Link><h1 class="mt-3 break-all font-brand text-2xl font-semibold" dir="ltr">{{ cmsPage.slug }}</h1><p class="mt-2 text-xs text-muted-foreground">{{ cmsEvent.name }}</p></div><Badge :variant="dirty ? 'secondary' : 'outline'" role="status">{{ t(dirty ? 'cms.unsaved' : 'cms.savedState') }}</Badge></div>
        <div class="mb-6 flex flex-wrap gap-2"><Button :disabled="busy || (!dirty && !!draftId)" @click="saveDraft">{{ busy ? t('saving') : t('cms.saveDraft') }}</Button><Button variant="outline" :disabled="busy" @click="showPreview">{{ t('cms.preview') }}</Button><Button variant="outline" :disabled="busy || !publishable" @click="restoreTarget = null; confirmation = true">{{ t('cms.publish') }}</Button><Button variant="ghost" :disabled="busy" @click="reloadState">{{ t('cms.reload') }}</Button></div>
        <p v-if="!canPublish(snapshot) || dirty" class="mb-5 text-xs text-muted-foreground">{{ t('cms.publishHelp') }}</p>
        <div v-if="error" role="alert" class="mb-6 border border-destructive p-4 text-sm"><p>{{ t(error) }}</p><ul v-if="Object.keys(fields).length" class="mt-2 list-inside list-disc"><li v-for="(messages, field) in fields" :key="field"><strong>{{ field }}</strong>: {{ messages.join(' ') }}</li></ul></div>
        <p v-if="notice" role="status" class="mb-5 text-sm text-primary">{{ t(notice) }}</p>
        <section class="mb-6 min-w-0 space-y-3 border border-border bg-card p-4" :aria-busy="mediaBusy" aria-labelledby="media-library-title">
            <h2 id="media-library-title" class="font-brand font-semibold">{{ t('media.library') }}</h2>
            <p class="text-xs text-muted-foreground">{{ t('media.uploadHelp') }}</p>
            <form class="flex flex-wrap items-end gap-3" @submit.prevent="uploadMedia"><div class="min-w-0 flex-1"><Label for="cms-media-upload">{{ t('media.upload') }}</Label><input id="cms-media-upload" ref="upload" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required :disabled="mediaBusy || busy" class="block w-full min-w-0 text-xs" /></div><Button type="submit" :disabled="mediaBusy || busy">{{ mediaBusy ? t('saving') : t('media.upload') }}</Button></form>
            <div class="flex flex-wrap gap-2"><Button type="button" variant="outline" :disabled="mediaBusy" @click="loadMedia()">{{ t('media.refresh') }}</Button><Button v-if="mediaPage.prev_page_url" type="button" variant="outline" :disabled="mediaBusy" @click="loadMedia(mediaPageNumber(mediaPage.prev_page_url))">{{ t('cms.previous') }}</Button><Button v-if="mediaPage.next_page_url" type="button" variant="outline" :disabled="mediaBusy" @click="loadMedia(mediaPageNumber(mediaPage.next_page_url))">{{ t('cms.next') }}</Button></div>
            <p v-if="mediaError" role="alert" class="text-sm text-destructive">{{ t(mediaError) }}</p>
            <p v-if="mediaNotice" role="status" class="text-sm">{{ t('media.uploaded') }}</p>
            <p v-if="mediaBusy" role="status" class="text-xs">{{ t('cms.loading') }}</p>
            <p v-else-if="!mediaPage.data.length" class="text-xs text-muted-foreground">{{ t('media.empty') }}</p>
        </section>
        <div class="grid min-w-0 gap-6 xl:grid-cols-[minmax(0,1fr)_19rem]">
            <fieldset class="min-w-0 space-y-5" :disabled="busy" :aria-busy="busy"><legend class="sr-only">{{ t('cms.editor') }}</legend>
                <section class="border border-border bg-card p-4 sm:p-5"><div class="grid gap-4 sm:grid-cols-2"><div><Label for="cms-language">{{ t('cms.contentLanguage') }}</Label><NativeSelect id="cms-language" v-model="locale"><option v-for="language in locales" :key="language" :value="language">{{ language.toUpperCase() }}</option></NativeSelect></div><div><Label for="cms-title-status">{{ t('cms.translationStatus') }}</Label><NativeSelect id="cms-title-status" :model-value="snapshot.locales[locale].status" @update:model-value="value => setStatus(snapshot.locales, locale, value as TranslationStatus, ['title'])"><option v-for="status in statuses" :key="status" :value="status">{{ t(`cms.status.${status}`) }}</option></NativeSelect></div></div><div class="mt-4" :dir="locale === 'ar' ? 'rtl' : 'ltr'"><Label for="cms-title">{{ t('cms.fields.title') }} · {{ locale.toUpperCase() }}</Label><Input v-if="snapshot.locales[locale].content" id="cms-title" v-model="snapshot.locales[locale].content!.title" maxlength="5000" :aria-invalid="!validField(snapshot.locales[locale].content!.title ?? '', 'text', snapshot.locales[locale].status)" aria-describedby="cms-title-help" /><p id="cms-title-help" class="mt-2 text-xs text-muted-foreground">{{ t(snapshot.locales[locale].content ? 'cms.textHelp' : 'cms.missingHelp') }}</p></div></section>
                <div class="flex flex-wrap items-end gap-2"><div class="min-w-0 flex-1"><Label for="cms-component">{{ t('cms.component') }}</Label><NativeSelect id="cms-component" v-model="componentKey"><option v-for="(_, key) in catalog" :key="key" :value="key">{{ t(`cms.components.${key}`) }}</option></NativeSelect></div><Button :disabled="snapshot.sections.length >= 100" @click="snapshot = addSection(snapshot, catalog, componentKey)">{{ t('cms.addSection') }}</Button></div>
                <p v-if="!snapshot.sections.length" class="border border-dashed border-border p-8 text-center text-sm text-muted-foreground">{{ t('cms.noSections') }}</p>
                <SectionEditor v-for="(section, index) in snapshot.sections" :key="section.id" v-model="snapshot.sections[index]!" :media-items="mediaPage.data" :definition="catalog[section.component_key]!" :locale="locale" :first="index === 0" :last="index === snapshot.sections.length - 1" :disabled="busy" :full="snapshot.sections.length >= 100" @action="action => sectionAction(index, action)" />
            </fieldset>
            <RevisionHistory :revisions="revisions" :audit="audit" :published="cmsPage.published_revision_id" :disabled="busy || dirty" @restore="confirmRestore" />
        </div>
        <DialogRoot v-model:open="confirmation" :dir="direction"><DialogContent><DialogTitle class="font-brand text-lg font-semibold">{{ t(restoreTarget ? 'cms.confirmRestore' : 'cms.confirmPublish') }}</DialogTitle><DialogDescription>{{ t(restoreTarget ? 'cms.restoreHelp' : 'cms.publishConfirmHelp') }}</DialogDescription><p v-if="restoreTarget">{{ t('cms.version', { version: restoreTarget.version }) }}</p><div class="flex flex-wrap gap-2"><Button :disabled="busy" @click="publishOrRestore">{{ t('cms.confirm') }}</Button><DialogClose as-child><Button variant="outline" :disabled="busy">{{ t('cms.cancel') }}</Button></DialogClose></div></DialogContent></DialogRoot>
        <DialogRoot v-model:open="previewOpen" :dir="direction"><DialogContent class="sm:max-w-3xl"><DialogTitle class="font-brand text-lg font-semibold">{{ t('cms.preview') }}</DialogTitle><DialogDescription>{{ t('cms.previewHelp') }}</DialogDescription><Label for="cms-preview-language">{{ t('cms.contentLanguage') }}</Label><NativeSelect id="cms-preview-language" v-model="locale"><option v-for="language in locales" :key="language" :value="language">{{ language.toUpperCase() }}</option></NativeSelect><div v-if="preview" class="space-y-4" :dir="locale === 'ar' ? 'rtl' : 'ltr'"><h2 class="font-brand text-xl font-semibold">{{ preview.locales[locale].content?.title ?? t('cms.status.missing') }}</h2><template v-for="section in preview.sections" :key="section.id"><section v-if="section.visible" class="border border-border p-4"><h3 class="mb-3 text-xs text-muted-foreground">{{ t(`cms.components.${section.component_key}`) }} · {{ t(`cms.variants.${section.variant}`) }}</h3><dl v-if="section.locales[locale].content" class="space-y-3"><div v-for="(value, field) in section.locales[locale].content" :key="field"><dt class="text-xs text-muted-foreground">{{ t(`cms.fields.${field}`) }}</dt><dd class="mt-1 whitespace-pre-wrap break-words text-sm">{{ value }}</dd></div></dl><p v-else class="text-sm">{{ t('cms.status.missing') }}</p><p v-if="section.component_key === 'countdown'" class="mt-3 text-xs text-muted-foreground">{{ t('cms.countdownNote') }}</p></section></template></div><DialogClose as-child><Button variant="outline">{{ t('cms.close') }}</Button></DialogClose></DialogContent></DialogRoot>
    </WorkspaceLayout>
</template>
