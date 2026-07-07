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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', 'Georgia', 'serif'],
            },
            colors: {
                maroon: {
                    DEFAULT: '#5c1616',
                    dark: '#3f0e0e',
                    light: '#7a2222',
                    50: '#fdf3f3',
                },
                gold: {
                    DEFAULT: '#c19a3d',
                    dark: '#a37f2c',
                    light: '#d9b969',
                    soft: '#e7d4a3',
                },
                cream: {
                    DEFAULT: '#faf5ec',
                    dark: '#f2e8d6',
                },
            },
            boxShadow: {
                card: '0 4px 20px -6px rgba(92, 22, 22, 0.12)',
            },
        },
    },

    plugins: [forms],
};
