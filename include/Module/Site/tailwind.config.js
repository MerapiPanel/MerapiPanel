/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./views/**/*.{html,js,twig}",
    "./views/*.{html,js,twig}",
    "./src/**/*.{html,js,twig}",
  ],
  theme: {
    extend: {},
    fontSize: {
      sm: '0.8rem',
      base: '0.95rem',
      xl: '1.15rem',
      '2xl': '1.363rem',
      '3xl': '1.653rem',
      '4xl': '2rem',
      '5xl': '3.052rem',
    }
  },
  plugins: [],
}

