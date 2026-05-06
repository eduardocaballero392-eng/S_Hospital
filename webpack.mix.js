const mix = require('laravel-mix');

mix.js('frontend/js/app.js', 'public/js')
   .react()
   .sass('frontend/sass/app.scss', 'public/css', {
       sassOptions: { quietDeps: true }
   })
   .sass('frontend/sass/landing.scss', 'public/css', {
       sassOptions: { quietDeps: true }
   })
   .sass('frontend/sass/paciente/agenda_cita.scss', 'public/css', {
       sassOptions: { quietDeps: true }
   });
