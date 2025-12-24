import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.svelte',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                medical: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0284c7',
                    600: '#0369a1',  // PRIMARY - darkened for WCAG AA contrast
                    700: '#075985',
                    800: '#0c4a6e',
                    900: '#082f49',
                },
                accent: {
                    400: '#a3e635',
                    500: '#84cc16',
                    600: '#65a30d',
                },
            },
        },
    },
    plugins: [],
};
