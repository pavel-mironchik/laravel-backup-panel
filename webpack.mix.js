const mix = require('laravel-mix');

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

mix
    .setPublicPath('public/vendor/backup_panel')
    .js('resources/js/app.js', '')
    .sass('resources/sass/app.scss', '')
    .version()
    .copy('public/vendor/backup_panel', '../laravel-backup-panel-test/public/vendor/backup_panel');
