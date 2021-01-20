const mix = require('laravel-mix');
const fs = require('fs-extra')

mix.options({
        terser: {
            extractComments: false,
        }
    })
    .setPublicPath('public/vendor/laravel_backup_panel')
    .js('resources/js/app.js', '').vue()
    .sass('resources/sass/app.scss', '')
    .version()
    .after(webpackStats => {
        fs.copy('public/vendor/laravel_backup_panel', '../laravel-backup-panel-test/public/vendor/laravel_backup_panel')
            .catch(err => console.error(err))
    })
