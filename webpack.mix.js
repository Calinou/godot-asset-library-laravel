const mix = require('laravel-mix');
require('laravel-mix-purgecss');
const postCssImport = require('postcss-import');
const postCssNesting = require('postcss-nesting');
const tailwindCss = require('tailwindcss');

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
  .js('resources/js/app.js', 'public/js')
  .extract(['@barba/core'])
  .postCss('resources/css/app.css', 'public/css', [postCssImport, postCssNesting, tailwindCss])
  .purgeCss();

if (mix.inProduction()) {
  mix.version();
}
