/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/frontend/**/*.blade.php",
    "./resources/js/main.js",
  ],
  theme: {
    extend: {
      colors: {
        'brand-dark': '#012836', // Deep teal from the screenshot
        'brand-orange': '#ff5e14', // Vibrant orange CTA
        'brand-light': '#f4f6f9', // Off-white section background
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'], 
      }
    },
  },
  plugins: [],
}