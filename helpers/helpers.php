<?php

use Illuminate\Support\Str;

if (!function_exists('resize_url')) {
	/**
	 * @param  string  $path
	 * @param  string  $size
	 * @param  array  $options  . Example ['retain' => true,'crop' => false,'watermark' => true,'bg' => 'ff6666']
	 * @return string
	 */
	function resize_url(string $path = '', string $size = 'thumbnail', array $options = []): string
	{

		$url = '';
		if (!str_contains($size, 'x')) {
			$url .= "$size/";
		}
		if (!Str::of($path)->startsWith('http')) {
			$url .= $path;
		}
		$url .= '?';

		if (Str::of($path)->startsWith('http')) {
			$url .= "url=$path";
		}
		if (str_contains($size, 'x')) {
			$size = explode('x', $size);
			$url .= "&w=$size[0]&h=$size[1]";
		}


		$url .= (isset($options['crop'])) ? "&crop={$options['crop']}" : '&crop=' . config('image.default.crop');
		$url .= (isset($options['retain'])) ? "&retain={$options['retain']}" : '&retain=' . config('image.default.retain');
		$url .= (isset($options['bg'])) ? "&bg={$options['bg']}" : '&bg=' . config('image.default.bg');
		$url .= (isset($options['watermark'])) ? "&watermark={$options['watermark']}" : '&watermark=' . config('image.default.watermark');

		return route(config('image.route.name'), ['url' => $url]);
	}
}