const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                yellow: {
                    // Mapeo dinámico usando variables CSS de la barbería
                    300: 'rgb(var(--primary-light) / <alpha-value>)',
                    400: 'rgb(var(--primary-light) / <alpha-value>)',
                    500: 'rgb(var(--primary-main) / <alpha-value>)',
                    600: 'rgb(var(--primary-dark) / <alpha-value>)',
                    700: 'rgb(var(--primary-dark) / <alpha-value>)',
                }
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
