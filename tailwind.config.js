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
        visage: ['Visage', 'sans-serif'],
        matonne: ['Matonne', 'sans-serif'],
      },
    },
  },
  plugins: [],
}

