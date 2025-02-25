/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/View/**/*.php",
  ],
  mode: 'jit',
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#4F46E5',
          focus: '#4338CA',
        },
      },
    },
  },
  future: {
    hoverOnlyWhenSupported: true,
  },
  safelist: [],
}
