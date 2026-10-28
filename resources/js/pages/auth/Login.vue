<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthShell from '../../components/AuthShell.vue';
defineProps<{ status?: string }>();
const access = useForm({ email: '', name: '' });
const verification = useForm({ email: '', code: '' });
function requestCode() {
    access.post('/auth/request', { onSuccess: () => { verification.email = access.email; verification.code = ''; } });
}
</script>

<template>
    <Head title="Sign in" />
    <AuthShell>
        <h1 class="font-serif text-4xl">Sign in or register</h1>
        <p class="mt-4 text-stone-600">Use your email to receive a code and a sign-in link. No password needed.</p>
        <p v-if="status" role="status" class="mt-4 rounded border border-teal-700 p-3">{{ status }}</p>
        <form class="mt-6 space-y-4" @submit.prevent="requestCode">
            <div><label for="name">Name (optional for new accounts)</label><input id="name" v-model="access.name" autocomplete="name" maxlength="255" class="auth-input"><p v-if="access.errors.name" role="alert">{{ access.errors.name }}</p></div>
            <div><label for="email">Email</label><input id="email" v-model="access.email" type="email" autocomplete="email" required maxlength="254" class="auth-input"><p v-if="access.errors.email" role="alert">{{ access.errors.email }}</p></div>
            <button class="auth-button" :disabled="access.processing">Send / resend code</button>
        </form>
        <form class="mt-8 space-y-4 border-t border-stone-300 pt-6" @submit.prevent="verification.post('/auth/verify-code')">
            <h2 class="text-xl font-semibold">Have a code?</h2>
            <div><label for="verify-email">Email receiving the code</label><input id="verify-email" v-model="verification.email" type="email" autocomplete="email" required class="auth-input"><p v-if="verification.errors.email" role="alert">{{ verification.errors.email }}</p></div>
            <div><label for="code">Six-digit code</label><input id="code" v-model="verification.code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required class="auth-input"><p v-if="verification.errors.code" role="alert">{{ verification.errors.code }}</p></div>
            <button class="auth-button" :disabled="verification.processing">Verify and sign in</button>
        </form>
    </AuthShell>
</template>
