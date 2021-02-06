const mix = require('laravel-mix');
const path = require('path');

mix.js('resources/js/app.js', 'public/js').vue({ version: 2 });

mix.sass('resources/sass/app.scss', 'public/css');

mix.webpackConfig({
    resolve: {
        alias: {
            '@lit-js': path.resolve(__dirname, 'resources/js/'),
            '@lit-sass': path.resolve(__dirname, 'resources/sass/'),
            vue$: 'vue/dist/vue',
        },
    },
});
