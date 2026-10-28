<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthShell from '../../components/AuthShell.vue';
const form = useForm({ email: '', password: '' });
</script>

<template>
    <Head title="Local demo sign-in" />
    <AuthShell>
        <h1 class="font-serif text-4xl">Local demo sign-in</h1>
        <p class="my-6 text-stone-600">Use a synthetic demo account provisioned for this local preview.</p>
        <form class="space-y-4" @submit.prevent="form.post('/dev/login', { onFinish: () => form.reset('password') })">
            <div><label for="email">Demo email</label><input id="email" v-model="form.email" type="email" autocomplete="username" required class="auth-input"></div>
            <div><label for="password">Password</label><input id="password" v-model="form.password" type="password" autocomplete="current-password" required class="auth-input"></div>
            <p v-for="error in form.errors" :key="error" role="alert">{{ error }}</p>
            <button class="auth-button" :disabled="form.processing">Sign in</button>
        </form>
    </AuthShell>
</template>
