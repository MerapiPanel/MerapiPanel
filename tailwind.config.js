/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/*.{html,js,twig}","./src/view/**/*.{html,js,twig}", "./src/modules/**/html_guest/*.{html,js,twig}", "./src/modules/**/html_admin/*.{html,js,twig}"],
  theme: {
    extend: {},
  },
  plugins: [],
}

