const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./app/View/Components/**/*.php"
    ],

    theme: {

        extend: {
            fontFamily: {
                'roboto': ['Roboto'],
                'manrope': ['Manrope'],
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                sky: {
                    50: '#F0F9FF',
                    100: '#D3EDFF',
                    200: '#B6E1FF',
                    300: '#8ACEFF',
                    400: '#5FBCFF',
                    500: '#31A8FF',
                    600: '#0798FF',
                    700: '#0073C5',
                    800: '#004D83',
                    900: '#002642',
                    950: '#000D16',
                },
                teal: {
                    50: '#F0FFFE',
                    100: '#C5FFFA',
                    200: '#99FFF7',
                    300: '#6DFFF3',
                    400: '#42FFEF',
                    500: '#31FFEF',
                    600: '#07FFEA',
                    700: '#00C5B4',
                    800: '#008378',
                    900: '#00423C',
                    950: '#001614',
                },
                blue: {
                    50: '#F0F2FF',
                    100: '#C5CAFF',
                    200: '#99A2FF',
                    300: '#7C87FF',
                    400: '#505FFF',
                    500: '#3141FF',
                    600: '#071CFF',
                    700: '#0010C5',
                    800: '#000B83',
                    900: '#000542',
                    950: '#000216',
                }
            }
        },
        fontSize: {
            'xs': '.75rem',
            'sm': '.875rem',
            'tiny': '.925rem',
            'base': '1rem',
            'lg': '1.125rem',
            'xl': '1.25rem',
            '2xl': '1.5rem',
            '3xl': '1.875rem',
            '4xl': '2.25rem',
            '5xl': '3rem',
            '6xl': '4rem',
            '7xl': '5rem',
        }
    },

    corePlugins: {
        container: false,
    },

    plugins: [require('@tailwindcss/forms')],
};
