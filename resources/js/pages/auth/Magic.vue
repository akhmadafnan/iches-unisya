<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthShell from '../../components/AuthShell.vue';
import { useI18n } from 'vue-i18n';
import { authError } from '../../i18n';
const { t } = useI18n();
const form = useForm({});
</script>

<template>
    <Head :title="t('auth.confirm')" />
    <AuthShell>
        <h1 class="font-brand text-2xl font-semibold">{{ t('auth.confirm') }}</h1>
        <p class="mt-2 mb-6 text-sm text-muted-foreground">{{ t('auth.confirmHelp') }}</p>
        <form @submit.prevent="form.post('/auth/magic')">
            <p v-for="error in form.errors" :key="error" role="alert" class="mb-4">{{ authError(error) }}</p>
            <button class="auth-button w-full" :disabled="form.processing">{{ t('auth.confirmAction') }}</button>
        </form>
        <Link href="/login" class="mt-6 inline-block underline">{{ t('auth.newCode') }}</Link>
    </AuthShell>
</template>
