import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'motiveo-primary': '#6366f1',
                'motiveo-secondary': '#8b5cf6',
                'motiveo-accent': '#06b6d4',
                'motiveo-success': '#10b981',
                'motiveo-warning': '#f59e0b',
                'motiveo-pink': '#ec4899',
                'motiveo-dark': '#1e1b4b'
            },
            backgroundColor: {
                light: '#ffffff',
                dark: '#111827'
            }
        },
    },

    plugins: [forms],
};
