const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': '#5C318C',
                'selected-button': '#A3A3FF',
                'subtitle-dark': '#61646B',
                'subtle-light': '#909090',
                'danger': '#DA4040',
                'success': '#10B981',
                'warning': '#F59E0B',
                'info': '#3B82F6',
                'dark': '#111827',
                'light': '#F9FAFB',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
