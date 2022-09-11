# This image on the fly resize package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sz4h/image.svg?style=flat-square)](https://packagist.org/packages/space/image)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sz4h/image/run-tests?label=tests)](https://github.com/space/image/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/sz4h/image/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/space/image/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sz4h/image.svg?style=flat-square)](https://packagist.org/packages/space/image)

This package simplifies the resized version of stored images. It's support both local images and remote ones. It uses
the intervention/image.

### Requirements

- PHP >= 8.0
- fileinfo php extension
- GD Library OR Imagick PHP extension

### Todo

Apply some kind of cache mechanism on top of the resize core

## Installation

You can install the package via composer:

```bash
composer require sz4h/image
```

You should publish the config/assets file with:

```bash
php artisan vendor:publish --tag="image-config"
php artisan vendor:publish --tag="image-assets"
```

This is the contents of the published config file:

```php
// config for Space/Image
return [

	'route' => [
		/*
		 * The route domain used for on fly resize (Can be helpful in multi-tenant system)
		 */
		'domain' => false,

		/*
		 * The route name used for on fly resize
		 */
		'name' => 'resize',

		/*
		 * The route prefix used for on fly resize
		 */
		'prefix' => 'resize',

		/*
		 * The middlewares for resize route
		 */
		'middlewares' => [
			'web',
		],
	],

	'watermark' => [
		/*
		 * Watermark image path
		 * You may need to do a vendor:publish --tag=space-watermark
		 * Or Add the watermark image into the provided path
		 * If you face an Exception on type CouldNotRetrieveWatermark
		 */
		'image' => public_path('vendor/image/watermark.png'),

		/*
		 * Watermark image position on the image
		 * Accepted values are:
		 * bottom-center
		 * bottom-right
		 * bottom-left
		 * top-center
		 * top-right
		 * top-left
		 * center-center
		 * center-left
		 * center-right
		 */
		'position' => 'bottom-right',


		/*
		 * Offset horizontally of the watermark can be positive or negative value
		*/
		'offsetX' => 10,

		/*
		 * Offset vertically of the watermark can be positive or negative value
		*/
		'offsetY' => 10,

		/*
		 * Percentage ratio between the resized image and the watermark
		 */
		'ratio' => 0.20,
	],

	/*
	 * Presets for faster sizes creations
	 * Param:
	 * w => width of the image (Type: integer)
	 * h => height of the image (Type: integer)
	 * crop => used to allow cropping (Type: boolean)
	 * retain => used to retain the aspect ratio of the image. If used with the crop then the crop will determinate the nearest value for both width and height (Type: boolean)
	 * bg => background color in hex format (Type: string)
	 * watermark => used to allow watermark of the image (Type: boolean)
	 */
	'presets' => [
		'thumbnail' => 'w=150&h=150&crop=true&retain=false&bg=ffffff&watermark=false',
		'medium' => 'w=300&h=300&crop=true&retain=true&bg=ffffff&watermark=false',
		'large' => 'w=1024&h=1024&crop=true&retain=true&bg=ffffff&watermark=true',
	],

	/*
	 * Define the fallback image if the image not found
	 */
	'not_found_image_path' => public_path('vendor/image/not-found.png'),

	/*
	 * Default values
	 */
	'default' => [
		'w' => 200,
		'h' => 200,
		'crop' => true,
		'retain' => true,
		'bg' => null,
		'watermark' => false,
	],
];

```

## Usage

```php
echo resize_url(
    'storage/default/1/2022-08-15-9d5862c703.png',
    'thumbnail',
    [
        'retain' => true,
        'crop'=> true,
        'watermark'=> true,
        'bg'=> 'ff6666',
    ]
);
echo resize_url(
    'storage/default/1/2022-08-15-9d5862c703.png',
    '300x200'
);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/sz4h/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ahmed Safaa](https://github.com/mello21century)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
