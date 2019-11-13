// https://tailwindcss.com/docs/configuration

module.exports = {
  theme: {
    container: {
      center: true,
      padding: '0.75rem',
    },
    extend: {
      colors: {
        indigo: {
          // Hero background color from the main godotengine.org website
          700: '#333f67',
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
};
