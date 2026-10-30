<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthShell from '../../components/AuthShell.vue';
import { useI18n } from 'vue-i18n';
import { authError } from '../../i18n';
import { nextTick, ref } from 'vue';
const { t } = useI18n();
const props = defineProps<{ status?: string }>();
const verifying = ref(!!props.status);
const access = useForm({ email: '', name: '' });
const verification = useForm({ email: '', code: '' });
async function showVerification() {
    verification.email = access.email || verification.email;
    verifying.value = true;
    await nextTick();
    document.getElementById(verification.email ? 'code' : 'verify-email')?.focus();
}
async function showRequest() {
    access.email = verification.email;
    verifying.value = false;
    await nextTick();
    document.getElementById('email')?.focus();
}
function requestCode() {
    access.post('/auth/request', { onSuccess: () => { verification.reset('code'); verification.clearErrors(); showVerification(); } });
}
</script>

<template>
    <Head :title="t('auth.signIn')" />
    <AuthShell>
        <h1 class="font-brand text-2xl font-semibold">{{ verifying ? t('auth.haveCode') : t('auth.register') }}</h1>
        <p class="mt-2 text-sm text-muted-foreground">{{ t('auth.description') }}</p>
        <p v-if="status && verifying" role="status" class="mt-4 rounded border border-primary p-3 text-sm">{{ t('auth.sent') }}</p>
        <form v-if="!verifying" class="mt-6 space-y-4" :aria-busy="access.processing" @submit.prevent="requestCode">
            <div><label for="name">{{ t('auth.name') }}</label><input id="name" v-model="access.name" autocomplete="name" maxlength="255" class="auth-input" :aria-invalid="!!access.errors.name"><p v-if="access.errors.name" role="alert">{{ authError(access.errors.name) }}</p></div>
            <div><label for="email">{{ t('auth.email') }}</label><input id="email" v-model="access.email" type="email" dir="ltr" autocomplete="email" required maxlength="254" class="auth-input" :aria-invalid="!!access.errors.email"><p v-if="access.errors.email" role="alert">{{ authError(access.errors.email) }}</p></div>
            <button class="auth-button w-full" :disabled="access.processing">{{ t('auth.send') }}</button>
            <button type="button" class="min-h-11 w-full text-sm underline underline-offset-4" :disabled="access.processing" @click="showVerification">{{ t('auth.haveCode') }}</button>
        </form>
        <form v-else class="mt-6 space-y-4" :aria-busy="verification.processing" @submit.prevent="verification.post('/auth/verify-code')">
            <div><label for="verify-email">{{ t('auth.receivingEmail') }}</label><input id="verify-email" v-model="verification.email" type="email" dir="ltr" autocomplete="email" required class="auth-input" :aria-invalid="!!verification.errors.email"><p v-if="verification.errors.email" role="alert">{{ authError(verification.errors.email) }}</p></div>
            <div><label for="code">{{ t('auth.code') }}</label><input id="code" v-model="verification.code" dir="ltr" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required class="auth-input" :aria-invalid="!!verification.errors.code"><p v-if="verification.errors.code" role="alert">{{ authError(verification.errors.code) }}</p></div>
            <button class="auth-button w-full" :disabled="verification.processing">{{ t('auth.verify') }}</button>
            <button type="button" class="min-h-11 w-full text-sm underline underline-offset-4" :disabled="verification.processing" @click="showRequest">{{ t('auth.newCode') }}</button>
        </form>
    </AuthShell>
</template>
