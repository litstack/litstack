const mix = require('laravel-mix');

mix.js('fjord/resources/js/app.js', 'public/fjord/js').sass(
    'fjord/resources/sass/app.scss',
    'public/fjord/css'
);

// Fjord
mix.webpackConfig({
    resolve: {
        alias: {
            '@fj-js': __dirname,
            '@fj-sass': path.resolve(__dirname, '../sass/')
        }
    }
});

module.exports = {};
