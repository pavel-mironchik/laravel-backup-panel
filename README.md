# Laravel Backup Panel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pavel-mironchik/laravel-backup-panel.svg?style=flat-square)](https://packagist.org/packages/pavel-mironchik/laravel-backup-panel)
[![Build Status](https://img.shields.io/travis/pavel-mironchik/laravel-backup-panel/master.svg?style=flat-square)](https://travis-ci.org/pavel-mironchik/laravel-backup-panel)
[![Quality Score](https://img.shields.io/scrutinizer/g/pavel-mironchik/laravel-backup-panel.svg?style=flat-square)](https://scrutinizer-ci.com/g/pavel-mironchik/laravel-backup-panel)
[![Total Downloads](https://img.shields.io/packagist/dt/pavel-mironchik/laravel-backup-panel.svg?style=flat-square)](https://packagist.org/packages/pavel-mironchik/laravel-backup-panel)

Laravel Backup Panel provides a dashboard for [spatie/laravel-backup](https://github.com/spatie/laravel-backup) package.

![Screenshot](https://i.imgur.com/LeDlIuU.png)

## Installation

Before using this package make sure you've installed and configured [spatie/laravel-backup](https://github.com/spatie/laravel-backup) package!

You can install the package via composer:

```bash
$ composer require pavel-mironchik/laravel-backup-panel
```

Then publish assets:

```bash
$ php artisan vendor:publish --tag=laravel-backup-panel-assets
```

By default, you will only be able to access the dashboard in the `local` environment. 
To change that:

1. Publish the `LaravelBackupPanelServiceProvider`

```bash
$ php artisan vendor:publish --tag=laravel-backup-panel-provider
```

2. Add it to the list of application providers in your `app.php` file (anywhere below `EventServiceProvider`)

```php
'providers' => [

    ...

    /*
     * Application Service Providers...
     */
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    // App\Providers\BroadcastServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\LaravelBackupPanelServiceProvider::class,

    ...
],
```

3. Modify authorization gate in your provider

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
            'admin@your-site.com'
        ]);
    });
}
```

### Upgrading

When upgrading the package, do not forget to re-publish assets:

```bash
$ php artisan vendor:publish --tag=laravel-backup-panel-assets --force
```

## Configuration

Default value of the URI path where Laravel Backup Panel will be accessible from is `backup`. To change it you must publish config file:

```bash
$ php artisan vendor:publish --tag=laravel-backup-panel-config
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