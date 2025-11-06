import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'purple-bg': '#F5F3FF',      // Latar belakang yang sangat terang
                'purple-card': '#EDE9FE',    // Untuk kartu atau bagian
                'purple-primary': '#8B5CF6', // Warna utama untuk tombol/tautan
                'purple-hover': '#7C3AED',   // Warna saat di-hover
            },
        },
    },

    plugins: [forms],
};
