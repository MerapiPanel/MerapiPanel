/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/*.{html,js,twig}",
    "./src/views/**/*.{html,js,twig}",
    "./src/module/**/html_admin/*.{html,js,twig}",
    "./src/module/**/html_public/*.{html,js,twig}",
    "./src/module/**/html_parts/*.{html,js,twig}"
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('tailwind-fontawesome')
  ],
}

