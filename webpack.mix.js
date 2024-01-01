const mix = require('laravel-mix');
const DeadCodePlugin = require('webpack-deadcode-plugin');

const webpackConfig = {
        plugins: [
    new DeadCodePlugin({
        patterns: [
            'src/**/*.(js|jsx|css)',
        ],
        exclude: [
            '**/*.(stories|spec).(js|jsx)',
        ],
    })
]
}
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .vue().webpackConfig({
    output: { chunkFilename: 'js/chunks/[name].js?id=[chunkhash]' },
});
   // .less('resources/less/app.scss', 'public/css');
mix.js('resources/js/admin/admin.js', 'public/js')
    .vue()
mix.js('resources/js/Cabinet/cabinet.js', 'public/js')
    .vue()


