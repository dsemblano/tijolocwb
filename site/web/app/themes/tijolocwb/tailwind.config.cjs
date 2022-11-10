// https://tailwindcss.com/docs/configuration
module.exports = {
  content: ['./index.php', './app/**/*.php', './resources/**/*.{php,vue,js}', './node_modules/flowbite/**/*.js'], 
  theme: {
    extend: {
      colors: {
        tijolo: '#E2AA9F',
        tijologreen: '#A5AB9D',
        tijoloyellow: '#E2CB9F',
        tijolocardapio: '#dbc8b2',
        tijolohorarios: '#666b54',
        tijolopink: '#D68778',
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
};
