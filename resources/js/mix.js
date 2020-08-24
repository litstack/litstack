const mix = require('laravel-mix');

mix.js('lit/resources/js/app.js', 'public/lit/js').sass(
	'lit/resources/sass/app.scss',
	'public/lit/css'
);

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
