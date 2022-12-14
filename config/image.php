<?php

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
