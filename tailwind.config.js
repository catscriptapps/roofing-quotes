/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.php',
    './resources/js/**/*.js',
    './src/**/*.php',
    './index.php',
  ],
  safelist: [
    'flex', 'hidden', 'block', 'inline-block', 'grid', 'gap-4', 'p-4', 'm-4',
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Quicksand', 'sans-serif'],
      },
      colors: {
        // PRIMARY: Roofing Red
        primary: {
          '50': '#fef2f2',
          '100': '#fee2e2',
          '200': '#fecaca',
          '300': '#fca5a5',
          '400': '#f87171',
          '500': '#ef4444', // Base red
          '600': '#dc2626',
          '700': '#b91c1c',
          '800': '#991b1b',
          '900': '#7f1d1d',
          '950': '#450a0a',
        },

        // SECONDARY: Black / Charcoal
        secondary: {
          '50': '#f5f5f5',
          '100': '#e5e5e5',
          '200': '#d4d4d4',
          '300': '#a3a3a3',
          '400': '#737373',
          '500': '#525252',
          '600': '#404040',
          '700': '#262626',
          '800': '#171717',
          '900': '#0a0a0a',
          '950': '#000000', // Pure black
        },
      },
    },
  },
  plugins: [],
  darkMode: 'class',
}