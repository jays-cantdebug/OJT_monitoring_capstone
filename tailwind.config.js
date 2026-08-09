import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        // Replacing (not extending) Tailwind's default palette is deliberate:
        // the client's brand spec is a strict 5-color palette plus 3 semantic
        // status colors. Keeping the full default gray/blue/red/... scale
        // around would let old classes like `text-gray-500` keep compiling
        // and drift back in silently. With only these tokens defined, any
        // future non-brand color class simply won't produce any styling -
        // drift becomes an immediately visible bug instead of a slow leak.
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            white: '#FFFFFF',
            black: '#000000',
            'light-gray': '#E5E5E5',
            gold: '#FCA311',
            navy: '#14213D',
            success: '#16A34A',
            danger: '#DC2626',
            warning: '#D97706',
        },
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
