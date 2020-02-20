const mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        alias: {
            '@fj-js': path.resolve(__dirname, 'resources/js/'),
            '@fj-sass': path.resolve(__dirname, 'resources/sass/')
        }
    }
});

mix.sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/app.js', 'public/js/app.js')
    .options({
        fileLoaderDirs: {
            images: 'public/images'
        }
    });
