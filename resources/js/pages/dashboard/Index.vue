<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthShell from '../../components/AuthShell.vue';
defineProps<{ account: { name: string; email: string } }>();
const logout = useForm({});
</script>

<template>
    <Head title="Dashboard" />
    <AuthShell>
        <h1 class="font-serif text-4xl">Your account</h1>
        <p class="mt-6 text-xl">{{ account.name }}</p>
        <p class="mt-2 break-all text-stone-600">{{ account.email }}</p>
        <p class="my-8">You are signed in. Conference services are still being prepared.</p>
        <form @submit.prevent="logout.post('/logout')"><button class="auth-button" :disabled="logout.processing">Sign out</button></form>
    </AuthShell>
</template>
