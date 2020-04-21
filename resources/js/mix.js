const mix = require('laravel-mix');
console.log(__dirname);

exit();
mix.js('fjord/resources/js/app.js', 'public/fjord/js');

// Fjord
mix.webpackConfig({
    resolve: {
        alias: {
            '@fj-js': __dirname,
            '@fj-sass': path.resolve(__dirname, 'sass/')
        }
    }
});

module.exports = {};
