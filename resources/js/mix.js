const mix = require('laravel-mix');

mix.js('fjord/resources/js/app.js', 'public/fjord/js');

// Fjord
mix.webpackConfig({
    resolve: {
        alias: {
            '@fj-js': path.resolve(
                __dirname,
                '../../../../vendor/aw-studio/fjord/resources/js/'
            ),
            '@fj-sass': path.resolve(
                __dirname,
                '../../../../vendor/aw-studio/fjord/resources/sass/'
            )
        }
    }
});

module.exports = {};
