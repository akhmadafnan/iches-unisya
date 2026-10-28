<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import AuthShell from '../../components/AuthShell.vue';
const props = defineProps<{
    account: { name: string; email: string };
    workspaces: { key: string; label: string }[];
    activeWorkspace: string;
}>();
const logout = useForm({});
const workspace = useForm({ workspace: props.activeWorkspace });
watch(() => props.activeWorkspace, (key) => { workspace.workspace = key; });
</script>

<template>
    <Head title="Dashboard" />
    <AuthShell>
        <h1 class="font-serif text-4xl">Your account</h1>
        <p class="mt-6 text-xl">{{ account.name }}</p>
        <p class="mt-2 break-all text-stone-600">{{ account.email }}</p>
        <form class="mt-8 space-y-3" @submit.prevent="workspace.post('/me/workspace', { preserveScroll: true })" :aria-busy="workspace.processing">
            <label for="workspace" class="font-semibold">Switch workspace</label>
            <select id="workspace" v-model="workspace.workspace" class="auth-input" :disabled="workspace.processing" aria-describedby="workspace-current workspace-error">
                <option v-for="choice in workspaces" :key="choice.key" :value="choice.key">{{ choice.label }}</option>
            </select>
            <p id="workspace-current" role="status" class="break-words text-sm text-stone-600">Current: {{ workspaces.find(choice => choice.key === activeWorkspace)?.label }}</p>
            <p id="workspace-error" role="alert" class="text-sm text-red-800">{{ workspace.errors.workspace }}</p>
            <button class="auth-button w-full sm:w-auto" :disabled="workspace.processing || workspace.workspace === activeWorkspace">{{ workspace.processing ? 'Switching…' : 'Switch workspace' }}</button>
        </form>
        <p class="my-8">You are signed in. Conference services are still being prepared.</p>
        <form @submit.prevent="logout.post('/logout')"><button class="auth-button" :disabled="logout.processing">Sign out</button></form>
    </AuthShell>
</template>
