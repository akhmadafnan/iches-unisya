<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthShell from '../../components/AuthShell.vue';
import { useI18n } from 'vue-i18n';
import { authError } from '../../i18n';
const { t } = useI18n();
const form = useForm({ email: '', password: '' });
</script>

<template>
    <Head :title="t('auth.demo')" />
    <AuthShell>
        <h1 class="font-brand text-2xl font-semibold">{{ t('auth.demo') }}</h1>
        <p class="mt-2 mb-6 text-sm text-muted-foreground">{{ t('auth.demoHelp') }}</p>
        <form class="space-y-4" @submit.prevent="form.post('/dev/login', { onFinish: () => form.reset('password') })">
            <div><label for="email">{{ t('auth.demoEmail') }}</label><input id="email" v-model="form.email" type="email" dir="ltr" autocomplete="username" required class="auth-input"></div>
            <div><label for="password">{{ t('auth.password') }}</label><input id="password" v-model="form.password" type="password" autocomplete="current-password" required class="auth-input"></div>
            <p v-for="error in form.errors" :key="error" role="alert">{{ authError(error) }}</p>
            <button class="auth-button w-full" :disabled="form.processing">{{ t('auth.signIn') }}</button>
        </form>
    </AuthShell>
</template>
