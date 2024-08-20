# Paperless

[![Latest Version on Packagist](https://img.shields.io/packagist/v/javaabu/paperless.svg?style=flat-square)](https://packagist.org/packages/javaabu/paperless)
[![Test Status](../../actions/workflows/run-tests.yml/badge.svg)](../../actions/workflows/run-tests.yml)
![Code Coverage Badge](./.github/coverage.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/javaabu/paperless.svg?style=flat-square)](https://packagist.org/packages/javaabu/paperless)

Application workflow handling on steroids

## Documentation

You'll find the documentation on [https://docs.javaabu.com/docs/paperless](https://docs.javaabu.com/docs/paperless).

Find yourself stuck using the package? Found a bug? Do you have general questions or suggestions for improving this package? Feel free to create an [issue](../../issues) on GitHub, we'll try to address it as soon as possible.

If you've found a bug regarding security please mail [info@javaabu.com](mailto:info@javaabu.com) instead of using the issue tracker.

## Installation
To get started, you need to install the package via Composer:

```bash
composer require javaabu/paperless
```

After installing the package, you need to publish the configuration file:

```bash
php artisan vendor:publish --provider="Javaabu\Paperless\PaperlessServiceProvider" --tag="paperless-config"
```

This will create a `paperless.php` file in your `config` directory.

Next, you need to publish the migrations:

```bash
php artisan vendor:publish --provider="Javaabu\Paperless\PaperlessServiceProvider" --tag="paperless-migrations"
```

After publishing the migrations, you can create the tables by running the migrations:

```bash
php artisan migrate
```


## Testing

You can run the tests with

``` bash
./vendor/bin/phpunit
```





## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [info@javaabu.com](mailto:info@javaabu.com) instead of using the issue tracker.

## Credits

- [Javaabu Pvt. Ltd.](https://github.com/javaabu)
- [Hussain Afeef (@ibnnajjaar)](https://abunooh.com)
- [Athfan Khaleel (@athphane)](https://athfan.com)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
