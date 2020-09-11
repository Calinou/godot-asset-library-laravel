// https://tailwindcss.com/docs/configuration

const { colors } = require('tailwindcss/defaultTheme')

module.exports = {
  theme: {
    container: {
      center: true,
      padding: '0.75rem',
    },
    extend: {
      colors: {
        // Add ultra-dark color variants for use in the dark theme
        blue: {
          ...colors.blue,
          1000: '#162d4f',
        },
        green: {
          ...colors.green,
          1000: '#12341d',
        },
        indigo: {
          // Hero background color from the main godotengine.org website
          700: '#333f67',
        },
        red: {
          ...colors.red,
          1000: '#441a1a',
        },
        yellow: {
          ...colors.yellow,
          1000: '#343210',
        },
      },
      borderColor: {
        // Make the default border color customizable via a CSS variable
        default: 'var(--border-color)',
      },
      screens: {
        // See <https://github.com/tailwindcss/tailwindcss/issues/1145>
        dark: {
          raw: '(prefers-color-scheme: dark)',
        },
        light: {
          raw: '(prefers-color-scheme: light)',
        },
      },
      spacing: {
        // Used to enforce a 16:9 aspect ratio on images while avoiding reflows
        // during loading
        '9/16': '56.25%',
        // Used for asset icon sizes
        26: '6.5rem',
      },
    },
  },
  purge: {
    mode: 'layers',
    content: ['resources/views/**/*.blade.php'],
  },
};
