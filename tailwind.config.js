import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
const plugin = require('tailwindcss/plugin');
const Color = require('color');

module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.vue',
        './resources/**/*.js',
        './public/**/*.html',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#1e40af',
                secondary: '#9333ea',
                dark: {
                    bg: '#1f2937',
                    text: '#f9fafb',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    darkMode: 'class', // Alternativamente, puedes usar 'media'
    plugins: [forms],
};
