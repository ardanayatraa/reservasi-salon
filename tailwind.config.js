const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: '#C9A57F', // Soft gold
                secondary: '#F9F5F0', // Cream white
                dark: '#1A1A1A',
                light: '#FFFFFF',
                accent: '#D6AD60', // Warm gold
                sidebar: {
                    bg: '#1A1A1A',
                    text: '#F9F5F0',
                    hover: '#C9A57F',
                },
            },
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                serif: ['Cormorant Garamond', 'serif'],
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
