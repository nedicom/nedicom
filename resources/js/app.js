import './bootstrap';
import '../css/app.css';
import 'vue-final-modal/style.css';

import { createSSRApp, h } from 'vue'
import { createInertiaApp, InertiaLink } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import {createVfm} from 'vue-final-modal';
import { Inertia } from '@inertiajs/inertia';
import 'flowbite';

const vfm = createVfm();

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createSSRApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vfm)
            //.use(InertiaLink)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563', showSpinner: true});

Inertia.on('navigate', (event) => {
    const path = event.detail.page.url;
    //ym(24900584, 'hit', path);
});