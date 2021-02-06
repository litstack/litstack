const mix = require('laravel-mix');
const path = require('path');

mix.js('lit/resources/js/app.js', 'public/lit/js').vue({ version: 2 });

mix.sass('lit/resources/sass/app.scss', 'public/lit/css');

// Lit
mix.webpackConfig({
    resolve: {
        alias: {
            '@lit-js': __dirname,
            '@lit-sass': path.resolve(__dirname, '../sass/'),
        },
    },
});

module.exports = {};
