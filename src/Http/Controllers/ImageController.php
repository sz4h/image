<?php

namespace Space\Image\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotFoundException;
use Intervention\Image\Facades\Image;
use Masterminds\HTML5\Exception;
use Space\Image\Exceptions\CanNotHandleNonImageType;

class ImageController extends Controller
{
	private null|string $url;

	private Collection $params;

	public function resize(null|string $url = null)
	{
		$this->url = $url;
		$this->setParams();

		// Width
		$w = (int) $this->params->get('w');
		// Height
		$h = (int) $this->params->get('h');
		// Ratio
		$r = (bool) $this->params->get('retain');
		// Watermark
		$wm = (bool) $this->params->get('watermark');
		// Crop
		$c = (bool) $this->params->get('crop');
		// Background
		$bg = empty($this->params->get('bg')) ? null : $this->params->get('bg');

		$path = ($this->params->get('type') == 'local') ? public_path($this->params->get('url')) : $this->params->get('url');

		// Make the image
		// dd(public_path(Input::get('src')));
		try {
			$image = Image::make($path);
		} catch (NotFoundException | Exception $e) {
			$image = Image::make(config('sz4h-image.not_found_image_path'));
		}

		// Resize or Crop
		if ($c) {
			$image = Image::canvas($w, $h, $bg);
			try {
				$thumb = Image::make($path);
			} catch (NotFoundException | Exception $e) {
				$thumb = Image::make(config('sz4h-image.not_found_image_path'));
			}
			$thumb->resize($w, $h, function ($constraint) use ($r) {
				if ($r) {
					$constraint->aspectRatio();
				}
			});
			$image->insert($thumb, 'center', 0, 0);
			// $image->resize($w,$h,$r)->crop($w,$h);
		} else {
			$image->resize($w, $h, function ($constraint) use ($r) {
				if ($r) {
					$constraint->aspectRatio();
				}
			});
		}

		if ($wm) {
			try {
				$watermarkImage = Image::make(config('sz4h-image.watermark.image'));
			} catch (NotFoundException | Exception $e) {
				$watermarkImage = Image::make(config('sz4h-image.not_found_image_path'));
			}
			$watermarkImage->resize(
				$w * config('sz4h-image.watermark.ratio'),
				$h * config('sz4h-image.watermark.ratio'),
				function ($constraint) {
					$constraint->aspectRatio();
				}
			);
			$image->insert(
				$watermarkImage,
				config('sz4h-image.watermark.position'),
				config('sz4h-image.watermark.offsetX'),
				config('sz4h-image.watermark.offsetY')
			);
		}

		// View The Image
		return $image->response();
	}

	protected function setParams()
	{
		$pathParts = collect(explode('/', $this->url));
		$presets = config('sz4h-image.presets');
		$this->params = collect([]);
		$type = 'local';
		$presetString = request()->getQueryString();

		/* Set remote image url */
		if (str_contains(request()->getQueryString(), 'url=')) {
			$type = 'remote';
			$this->params = $this->params->merge($this->getPreset(request()->getQueryString()));
		}

		/* Remove first path key if it is a preset defined */
		if (in_array($pathParts->first(), array_keys($presets))) {
			$presetString = $presets[$pathParts->first()];
			$pathParts->shift(1);
		}

		/* Add the remaining path as an url */
		if (!str_contains(request()->getQueryString(), 'url=')) {
			$this->params->put('url', $pathParts->join('/'));
		}

		$preset = $this->getPreset($presetString);
		$this->params = $this->params->merge($preset);
		$this->params->put('type', $type);


		/* Fall down to default params if not set by request */
		$this->defaultParams();

		if (!Str::of((string) $this->params->get('url'))->endsWith([
			'.jpg', '.gif', '.bmp', '.jpeg', '.bmp', '.webp', '.png'
		])) {
			throw new CanNotHandleNonImageType(last(explode('.', $this->params->get('url'))));
		};
	}

	/**
	 * @param  mixed  $selectedPreset
	 * @return Collection
	 */
	protected function getPreset(mixed $selectedPreset): Collection
	{
		return collect(explode('&', $selectedPreset))
			->filter(fn($item) => str_contains($item, '='))
			->map(fn($item) => explode('=', $item))
			->keyBy('0')
			->map(fn($item) => @$item[1])
			->map(fn($item) => urldecode($item))
			->map(fn($item) => ($item === 'false' || $item == 0) ? false : $item);
	}

	protected function defaultParams()
	{
		if (!$this->params->has('w')) {
			$this->params->put('w', config('sz4h-image.default.w', 200));
		}
		if (!$this->params->has('h')) {
			$this->params->put('h', config('sz4h-image.default.h', 200));
		}
		if (!$this->params->has('crop')) {
			$this->params->put('crop', config('sz4h-image.default.crop', true));
		}
		if (!$this->params->has('retain')) {
			$this->params->put('retain', config('sz4h-image.default.retain', true));
		}
		if (!$this->params->has('bg')) {
			$this->params->put('bg', config('sz4h-image.default.bg'));
		}
		if (!$this->params->has('watermark')) {
			$this->params->put('watermark', config('sz4h-image.default.watermark', false));
		}
	}
}
