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
   })
   .sass('frontend/sass/medico/cita.scss', 'public/css/cita.css', {
       sassOptions: { quietDeps: true }
   })
   .sass('frontend/sass/medico/pacientes.scss', 'public/css/pacientes.css', {
       sassOptions: { quietDeps: true }
   })
   .sass('frontend/sass/medico/historial.scss', 'public/css/historial.css', {
       sassOptions: { quietDeps: true }
   })
   .sass('frontend/sass/paciente/diagnostico.scss', 'public/css/diagnostico.css', {
    sassOptions: { quietDeps: true }
});  