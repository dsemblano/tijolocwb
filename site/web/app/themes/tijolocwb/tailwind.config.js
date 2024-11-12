import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} config */
const config = {
  content: ['./app/**/*.php', './resources/**/*.{php,vue,js}'],
  theme: {
    container: {
      padding: {
      DEFAULT: '1rem',
      sm: '2rem',
      lg: '4rem',
      xl: '5rem',
    },
      center: true,
    },
    extend: {
      typography: {
        DEFAULT: {
          css: {
            maxWidth: '150ch', // add required value here
          }
        }
      },
      colors: {
        tijolo: '#E2AA9F',
        tijologreen: '#A5AB9D',
        tijologreentext: '#666A55',
        // tijologreentext: '#1C1C1C', //WCAG AAA compliance
        tijoloyellow: '#E2CB9F',
        tijolocardapio: '#dbc8b2',
        tijolohorarios: '#666b54',
        tijolopink: '#D68778',
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
  plugins: [typography],
};

export default config;
