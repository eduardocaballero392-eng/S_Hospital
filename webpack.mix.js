const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .react()  // ← Agrega esto para soporte React
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/landing.scss', 'public/css');