const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./node_modules/flowbite/**/*.js"
    ],

    theme: {
        extend: {
            keyframes: {
                customPulse: {               // Имя вашей анимации
                    '0%, 100%': { opacity: '1' },
                    '80%': { opacity: '0.8' }, // Можно изменить уровень "пульсации"
                },
            },
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            height: {
                '128': '32rem',
            },
            animation: {
                'spin-pulse': 'pulse 1s linear infinite',
                'custom-pulse': 'customPulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            fontSize: {
                xxs: '0.5rem',
            },
            transitionProperty: {
                'height': 'height ',
                'line-clamp-3': 'line-clamp-3',
            }
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography'), require('flowbite/plugin')],
};
