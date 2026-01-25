/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Cairo', 'sans-serif'],
            },
            colors: {
                china: {
                    red: '#E60012', // Vivid China Red
                    dark: '#B0000E', // Darker Red for hover/active
                    gold: '#FFD700', // Traditional Gold
                    yellow: '#F4E04D', // UI Yellow
                }
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
