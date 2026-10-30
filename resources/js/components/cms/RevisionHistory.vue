<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import type { Audit, Revision } from '@/lib/cms/schema';
import Button from '@/components/ui/Button.vue';
import Badge from '@/components/ui/Badge.vue';
defineProps<{ revisions: Revision[]; audit: Audit[]; published: string | null; disabled: boolean }>();
defineEmits<{ restore: [revision: Revision] }>();
const { t } = useI18n();
</script>

<template>
    <aside class="min-w-0 space-y-6">
        <section class="border border-border bg-card p-4"><h2 class="font-brand text-sm font-semibold">{{ t('cms.history') }}</h2><p class="mt-2 text-xs text-muted-foreground">{{ t('cms.historyLimit') }}</p>
            <p v-if="!revisions.length" class="mt-4 text-sm text-muted-foreground">{{ t('cms.noRevisions') }}</p>
            <ol class="mt-4 space-y-3"><li v-for="revision in revisions" :key="revision.id" class="border-t border-border pt-3"><div class="flex flex-wrap items-center justify-between gap-2"><span class="text-sm">{{ t('cms.version', { version: revision.version }) }}</span><Badge v-if="revision.id === published">{{ t('cms.published') }}</Badge><Button v-else type="button" variant="outline" :disabled="disabled || !published" @click="$emit('restore', revision)">{{ t('cms.restore') }}</Button></div><time class="mt-2 block break-all text-xs text-muted-foreground" :datetime="revision.created_at ?? undefined">{{ revision.created_at }}</time></li></ol>
        </section>
        <section class="border border-border bg-card p-4"><h2 class="font-brand text-sm font-semibold">{{ t('cms.audit') }}</h2><p v-if="!audit.length" class="mt-4 text-xs text-muted-foreground">{{ t('cms.noAudit') }}</p><ol class="mt-4 space-y-3"><li v-for="entry in audit" :key="entry.id" class="border-t border-border pt-3 text-xs"><p>{{ t(`cms.actions.${entry.action}`) }}</p><time class="mt-1 block break-all text-muted-foreground" :datetime="entry.occurred_at ?? undefined">{{ entry.occurred_at }}</time></li></ol></section>
    </aside>
</template>
