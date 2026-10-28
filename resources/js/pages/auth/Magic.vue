<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthShell from '../../components/AuthShell.vue';
const form = useForm({});
</script>

<template>
    <Head title="Confirm sign-in" />
    <AuthShell>
        <h1 class="font-serif text-4xl">Confirm sign-in</h1>
        <p class="my-6 text-stone-600">Continue to verify your email and sign in to ICHES.</p>
        <form @submit.prevent="form.post('/auth/magic')">
            <p v-for="error in form.errors" :key="error" role="alert" class="mb-4">{{ error }}</p>
            <button class="auth-button" :disabled="form.processing">Confirm and sign in</button>
        </form>
        <Link href="/login" class="mt-6 inline-block underline">Request a new code or link</Link>
    </AuthShell>
</template>
