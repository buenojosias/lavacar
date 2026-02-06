import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    presets: [
        require('./vendor/tallstackui/tallstackui/tailwind.config.js')
    ],

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/tallstackui/tallstackui/src/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Andrich', ...defaultTheme.fontFamily.sans],
            },
            "colors": {
                "primary": {
                    50: "#EFF3FF",
                    100: "#E0E8FE",
                    200: "#BFD2FE",
                    300: "#A1BEFD",
                    400: "#79A9FC",
                    500: "#4593FC",
                    600: "#187FE7",
                    700: "#0F5EAE",
                    800: "#074079",
                    900: "#022245",
                    950: "#011630"
                }
            }
        },
    },

    plugins: [forms],
};
