const mix = require('laravel-mix');

mix.sass('resources/assets/sass/app.scss', 'public/css')
    .js('resources/assets/app.js', 'public/js/app.js')
    .options({
        fileLoaderDirs: {
            images: 'public/images'
        }
    });
