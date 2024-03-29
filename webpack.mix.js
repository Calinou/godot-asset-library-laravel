const mix = require('laravel-mix');
const postCssImport = require('postcss-import');
const postCssUrl = require('postcss-url');
const postCssNesting = require('postcss-nesting');
const tailwindCss = require('tailwindcss');
// const tailwindCssNesting = require('tailwindcss/nesting');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .ts('resources/ts/app.ts', 'public/js')
  .extract(['@barba/core'])
  .postCss(
    'resources/css/app.css',
    'public/css',
    [
      postCssImport,
      postCssUrl,
      postCssNesting,
      tailwindCss,
    ],
  );

if (mix.inProduction()) {
  mix.version();
}
