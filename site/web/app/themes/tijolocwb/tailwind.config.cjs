// https://tailwindcss.com/docs/configuration
module.exports = {
  content: ['./index.php', './app/**/*.php', './resources/**/*.{php,vue,js}', './node_modules/flowbite/**/*.js'], 
  theme: {
    extend: {
      colors: {
        tijolo: '#E2AA9F',
        tijologreen: '#A5AB9D',
        tijologreentext: '#666A55',
        // tijologreentext: '#1C1C1C', //WCAG AAA compliance
        tijoloyellow: '#E2CB9F',
        tijolocardapio: '#dbc8b2',
        tijolohorarios: '#666b54',
        tijolopink: '#D68778',
        tijolop: '#F9FFE0',
        tijologray: '#2B2B2B',
        top: {
          '85': '85%',
        }
      },
      height: {
        'mobile': '80vh',
        'desktop': '80vh',
        'img-lg': '45rem',
      },
      fontSize: {
      logohome: '20rem',
      logohomedesc: '5vh',
      },
      lineHeight: {
        home2: '0.8',
      }
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
};
