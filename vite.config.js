import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                silenceDeprecations: ["legacy-js-api"],
            }
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
            ],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
                compilerOptions: {
                    isCustomElement: (tag) => {
                        return tag.startsWith('VButton')
                    }
                },
            },
        }),
    ],

    // Днастройки для отладки SSR
    build: {
        sourcemap: true, // sourcemaps для отладки
        minify: process.env.NODE_ENV === 'production' ? 'esbuild' : false,
    },

    // Настройки для SSR разработки
    ssr: {
        noExternal: [
            '@inertiajs/vue3',
            'vue-final-modal',
            'laravel-vite-plugin',
        ],
        target: 'node', // Важно для SSR
        format: 'esm'   // Формат модуля для Node.js
    },

    // Дополнительные настройки для отладки
    define: {
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: true, // Детальные ошибки гидрации
        __VUE_OPTIONS_API__: true,
        __VUE_PROD_DEVTOOLS__: process.env.NODE_ENV !== 'production',
    },

    // Оптимизация для SSR
    optimizeDeps: {
        include: [
            '@inertiajs/vue3',
            'vue',
            'vue-final-modal'
        ]
    }
});