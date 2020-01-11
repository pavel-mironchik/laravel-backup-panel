# Laravel Backup Panel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pavel-mironchik/laravel-backup-panel.svg?style=flat-square)](https://packagist.org/packages/pavel-mironchik/laravel-backup-panel)
[![Build Status](https://img.shields.io/travis/pavel-mironchik/laravel-backup-panel/master.svg?style=flat-square)](https://travis-ci.org/pavel-mironchik/laravel-backup-panel)
[![Quality Score](https://img.shields.io/scrutinizer/g/pavel-mironchik/laravel-backup-panel.svg?style=flat-square)](https://scrutinizer-ci.com/g/pavel-mironchik/laravel-backup-panel)
[![StyleCI](https://github.styleci.io/repos/231844000/shield?branch=master)](https://github.styleci.io/repos/231844000)
[![Total Downloads](https://img.shields.io/packagist/dt/pavel-mironchik/laravel-backup-panel.svg?style=flat-square)](https://packagist.org/packages/pavel-mironchik/laravel-backup-panel)

Laravel Backup Panel provides a dashboard for [spatie/laravel-backup](https://github.com/spatie/laravel-backup) package.
It lets you:
- create a backup (full | only database | only files)
- check the health of your backups
- list all backups
- download a backup
- delete a backup

It resembles look and functionality of another Spatie package: [spatie/nova-backup-tool](https://github.com/spatie/nova-backup-tool), only it doesn't use polling.
_A "real-time" updates of a backups list isn't such a necessarily thing and an intensive polling can cause unexpected charges if you use services that require to pay per API requests, such as Google Cloud Storage.
Also, some users reported about hitting a rate limit of Dropbox API._

![Screenshot](https://i.imgur.com/jrqTPuJ.png)

## Requirements

Make sure you meet [the requirements for installing spatie/laravel-backup](https://docs.spatie.be/laravel-backup/v6/requirements).

## Installation

First you must install [spatie/laravel-backup](https://docs.spatie.be/laravel-backup) into your Laravel app. 
The installation instructions are [here](https://docs.spatie.be/laravel-backup/v6/installation-and-setup). 
When successful, running `php artisan backup:run` on the terminal should create a backup and `php artisan backup:list` should return a list with an overview of all backup disks.

You may use composer to install Laravel Backup Panel into your project:

```bash
$ composer require pavel-mironchik/laravel-backup-panel
```

After installing, publish it resources using provided Artisan command:

```bash
$ php artisan laravel-backup-panel:install
```

This will place assets into `public/laravel_backup_panel` directory, add config file `config/laravel_backup_panel.php`, and register service provider `app/Providers/LaravelBackupPanelServiceProvider.php`.

### Upgrading

When upgrading the package, do not forget to re-publish assets:

```bash
$ php artisan vendor:publish --tag=laravel-backup-panel-assets --force
```

## Configuration

Laravel Backup Panel exposes a dashboard at `/backup`. Change it in `config/laravel_backup_panel.php` file:

```php
'path' => 'backup',
```

By default, you will only be able to access the dashboard in the `local` environment. 
To change that, modify authorization gate in the `app/Providers/LaravelBackupPanelServiceProvider.php`:

```php
/**
 * Register the Laravel Backup Panel gate.
 *
 * This gate determines who can access Laravel Backup Panel in non-local environments.
 *
 * @return void
 */
protected function gate()
{
    Gate::define('viewLaravelBackupPanel', function ($user) {
        return in_array($user->email, [
            'admin@your-site.com',
        ]);
    });
}
```

## Usage

Open `http://your-site/backup`. You'll see a dashboard and controls to use.

### Testing

```bash
$ composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Development

Make sure you've prepared a dummy Laravel application to test the package in, because assets will be copied there by this line in `webpack.mix.js`:

```js
.copy('public/vendor/laravel_backup_panel', '../laravel-backup-panel-test/public/vendor/laravel_backup_panel');
```

### Security

If you discover any security related issues, please email mironchikpavel@gmail.com instead of using the issue tracker.

## Credits

- [Pavel Mironchik](https://github.com/pavel-mironchik)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.