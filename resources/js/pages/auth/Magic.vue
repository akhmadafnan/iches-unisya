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
        <h1 class="font-brand text-3xl font-semibold">{{ t('auth.confirm') }}</h1>
        <p class="my-6 text-muted-foreground">{{ t('auth.confirmHelp') }}</p>
        <form @submit.prevent="form.post('/auth/magic')">
            <p v-for="error in form.errors" :key="error" role="alert" class="mb-4">{{ authError(error) }}</p>
            <button class="auth-button" :disabled="form.processing">{{ t('auth.confirmAction') }}</button>
        </form>
        <Link href="/login" class="mt-6 inline-block underline">{{ t('auth.newCode') }}</Link>
    </AuthShell>
</template>
