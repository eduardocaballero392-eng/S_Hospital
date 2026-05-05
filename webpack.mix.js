const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .react()
   .sass('resources/sass/app.scss', 'public/css', {
       sassOptions: { quietDeps: true }
   })
   .sass('resources/sass/landing.scss', 'public/css', {
       sassOptions: { quietDeps: true }
   })
   .sass('resources/sass/paciente/agenda_cita.scss', 'public/css', {
       sassOptions: { quietDeps: true }
   });
