module.exports = {
  plugins: {
    'postcss-preset-env': { stage: 4 },
    // 'postcss-import': {},
    'tailwindcss/nesting': 'postcss-nesting',
    tailwindcss: {},
    autoprefixer: {},
  },
};
