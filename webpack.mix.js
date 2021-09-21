const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .sourceMaps(!mix.inProduction(), 'source-map')
   .extract()
   .sass('resources/css/app.scss', 'public/css')
   .copy('resources/assets/images', 'public/images')
   .options({
      processCssUrls: mix.inProduction(),
      postCss: [require('autoprefixer')],
   })
   .disableSuccessNotifications();

if (mix.inProduction()) {
   mix.version();
} else {
   mix.browserSync({
      proxy: process.env.APP_URL,
      files: [
         'resources/views/**/*',
         'config/**/*',
         // 'public/**/*',
         'public/js/app.js',
         'public/css/app.css',
      ],
   });
}
