import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dark: {
                    bg: '#F8FAFC',
                    card: '#FFFFFF',
                    border: '#E2E8F0',
                    text: '#0F172A',
                    muted: '#64748B',
                },
                brand: {
                    indigo: '#6366F1',
                    violet: '#8B5CF6',
                    glow: 'rgba(99, 102, 241, 0.12)',
                }
            },
            boxShadow: {
                'glass-inset': 'inset 0 1px 0 0 rgba(255, 255, 255, 0.6)',
                'glass': '0 8px 32px 0 rgba(148, 163, 184, 0.12)',
            }
        },
    },

    plugins: [forms],
};

