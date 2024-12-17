import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';
import clickOutside from './directives/clickOutside';
import ToastPlugin from 'vue-toast-notification';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        // Registrar plugins
        vueApp.use(plugin)
            .use(ZiggyVue)
            .use(ToastPlugin)
            .use(createPinia());

        // Registrar directiva personalizada
        vueApp.directive('click-outside', clickOutside);

        // Montar la aplicación
        vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
