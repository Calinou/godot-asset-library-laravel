// https://tailwindcss.com/docs/configuration

module.exports = {
  theme: {
    container: {
      center: true,
      padding: '0.75rem',
    },
    extend: {
      spacing: {
        // Used to enforce a 16:9 aspect ratio on images while avoiding reflows
        // during loading
        '9/16': '56.25%',
      },
    },
  },
};
