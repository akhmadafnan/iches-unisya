import '../css/app.css';
import '@fontsource-variable/inter';
import '@fontsource-variable/sora';
import '@fontsource-variable/noto-sans-arabic';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h, type DefineComponent } from 'vue';
import { i18n, syncLocale } from './i18n';
import type { Locale } from './types';
import { router } from '@inertiajs/vue3';

router.on('navigate', (event) => syncLocale(event.detail.page.props.locale as Locale));

void createInertiaApp({
    title: (title) => `${title} | ICHES UNISYA 2027`,
    resolve: (name) => resolvePageComponent(
        `./pages/${name}.vue`,
        import.meta.glob<DefineComponent>('./pages/**/*.vue'),
    ),
    setup({ el, App, props, plugin }) {
        syncLocale(props.initialPage.props.locale as Locale);
        createApp({ render: () => h(App, props) }).use(plugin).use(i18n).mount(el);
    },
});
