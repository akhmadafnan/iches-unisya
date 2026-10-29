<script setup lang="ts">
import { router, useForm, usePage } from '@inertiajs/vue3';
import { watch, ref, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';
import type { DashboardProps } from '../../types';
import { workspaceLabel } from '../../i18n';
import Button from '../ui/Button.vue';

const page = usePage<DashboardProps>();
const { t } = useI18n();
const failed = ref(false);
const form = useForm({ workspace: page.props.activeWorkspace });
watch(() => page.props.activeWorkspace, (value) => { form.workspace = value; });
const removeInvalid = router.on('invalid', (event) => {
    if (form.processing) { failed.value = true; event.preventDefault(); }
});
const removeException = router.on('exception', (event) => {
    if (form.processing) { failed.value = true; event.preventDefault(); }
});
onUnmounted(() => { removeInvalid(); removeException(); });
function submit() {
    failed.value = false;
    form.post('/me/workspace', {
        preserveScroll: true,
        onError: () => { failed.value = true; },
    });
}
</script>

<template>
    <form class="grid min-w-0 gap-3" :aria-busy="form.processing" @submit.prevent="submit">
        <label class="grid min-w-0 gap-2 text-sm font-medium">
            {{ t('switchWorkspace') }}
            <select v-model="form.workspace" class="shell-select w-full min-w-0" :disabled="form.processing" :aria-invalid="failed || !!form.errors.workspace" aria-describedby="workspace-error">
                <option v-for="choice in page.props.workspaces" :key="choice.key" :value="choice.key">{{ workspaceLabel(choice) }}</option>
            </select>
        </label>
        <p id="workspace-error" role="alert" class="text-sm text-red-800">{{ failed || form.errors.workspace ? t('failure') : '' }}</p>
        <Button variant="outline" :disabled="form.processing || form.workspace === page.props.activeWorkspace">{{ form.processing ? t('switching') : t('switchWorkspace') }}</Button>
    </form>
</template>
