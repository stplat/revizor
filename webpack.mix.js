const mix = require('laravel-mix');

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

mix.setPublicPath('www')
  .js('resources/js/app.js', 'www/js')
  // .copy([
  //   'resources/js/**/*.jpg',
  //   'resources/js/**/*.png',
  // ], 'www/images')
  .sass('resources/sass/common.scss', 'www/css')
  .sass('resources/sass/view-auth.scss', 'www/css')
  .sass('resources/sass/view-checks.scss', 'www/css')
  .sass('resources/sass/view-check.scss', 'www/css')
  .sass('resources/sass/view-recognitions.scss', 'www/css')
  .sass('resources/sass/view-users.scss', 'www/css')
  .sourceMaps();

mix.webpackConfig({
  output: {
    path: path.resolve(__dirname, 'www/'),
    publicPath: '/',
    chunkFilename: './js/[name].chunk.js'
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js')
    }
  }
});

mix.options({
  processCssUrls: false,
  // extractVueStyles: false,
  // purifyCss: false,
  // clearConsole: true
  optimization: { concatenateModules: false, providedExports: false, usedExports: false }
});
