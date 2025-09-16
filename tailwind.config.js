/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/**/*.{js,css}',
    './lib/**/*.php'
  ],
  theme: {
    extend: {
      colors: {
        primary: '#4466ff',
        'primary-dark': '#1a1a1a'
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif']
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography')
  ],
  safelist: [
    'grid-cols-1',
    'grid-cols-2', 
    'grid-cols-3',
    'grid-cols-4',
    'md:grid-cols-2',
    'md:grid-cols-3', 
    'md:grid-cols-4',
    'lg:grid-cols-2',
    'lg:grid-cols-3',
    'lg:grid-cols-4'
  ]
}
