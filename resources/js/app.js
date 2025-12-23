import { createInertiaApp } from '@inertiajs/svelte';
import { hydrate, mount } from 'svelte';
import '../css/app.css';

// Register Service Worker for PWA
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker
            .register('/sw.js')
            .then((registration) => {
                console.log('SW registered:', registration.scope);
            })
            .catch((error) => {
                console.log('SW registration failed:', error);
            });
    });
}

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true });
        return pages[`./Pages/${name}.svelte`];
    },
    setup({ el, App, props }) {
        if (el.dataset.serverRendered === 'true') {
            hydrate(App, { target: el, props });
        } else {
            mount(App, { target: el, props });
        }
    },
});
