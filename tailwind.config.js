/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#C19A6B', // Elegant Gold/Rose color for events
                dark: '#0A0A0A',
                glass: 'rgba(255, 255, 255, 0.05)',
            },
            fontFamily: {
                sans: ['Cairo', 'sans-serif'],
                outfit: ['Outfit', 'sans-serif'],
            }
        },
    },
    plugins: [],
}
