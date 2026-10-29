import { createI18n } from 'vue-i18n';
import type { Locale, Workspace } from '../types';
import en from './locales/en';
import id from './locales/id';
import ar from './locales/ar';

export const i18n = createI18n({ legacy: false, locale: 'en', fallbackLocale: 'en', messages: { en, id, ar } });

export function syncLocale(locale: Locale) {
    const valid = locale === 'ar' || locale === 'id' ? locale : 'en';
    i18n.global.locale.value = valid;
    document.documentElement.lang = valid;
    document.documentElement.dir = valid === 'ar' ? 'rtl' : 'ltr';
}

export function workspaceLabel(workspace: Workspace) {
    return workspace.role
        ? [workspace.event, workspace.institution, i18n.global.t(`roles.${workspace.role}`)].filter(Boolean).join(' / ')
        : i18n.global.t('personal');
}

export function authError(error = '') {
    const key = error.startsWith('Too many attempts') ? 'throttled'
        : error.startsWith('This code') ? 'invalidCode'
        : error.startsWith('This link') ? 'invalidLink'
        : error.startsWith('Unable to sign in') ? 'invalidCredentials' : 'invalid';
    return i18n.global.t(`auth.${key}`);
}
