<?php

namespace Space\Image\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotFoundException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image;
use Masterminds\HTML5\Exception;
use Space\Image\Exceptions\CanNotHandleNonImageType;
use Intervention\Image\Exceptions\DecoderException;

class ImageController extends Controller
{
    private null|string $url;

    private Collection $params;

    public function resize(null|string $url = null)
    {
        $this->url = $url;
        $this->setParams();


        // Make the image
        try {
            $image = Image::read($this->path);
        } catch (NotFoundException|Exception|DecoderException $e) {
            $image = Image::read(config('sz4h-image.not_found_image_path'));
        }

        // Resize or Crop
        if ($this->c) {
            $image = $image->cover($this->w, $this->h);
        } else {
            if ($this->r) {
                $image = $image->scale($this->w, $this->h);
            } else {
                $image = $image->resize($this->w, $this->h);
            }
        }

        if ($this->wm) {
            $image = $this->watermark($image);
        }

        // View The Image
        return response()
            ->make(
                $image->toWebp(),
                200,
                ['Content-Type' => 'image/webp']
            );
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

        if (!str((string)$this->params->get('url'))->lower()->endsWith([
            '.jpg', '.gif', '.bmp', '.jpeg', '.bmp', '.webp', '.png'
        ])) {
            throw new CanNotHandleNonImageType(last(explode('.', $this->params->get('url'))));
        };

        // Width
        $this->w = $this->params->get('w');
        // Height
        $this->h = $this->params->get('h');
        // Ratio
        $this->r = (bool)$this->params->get('retain');
        // Watermark
        $this->wm = (bool)$this->params->get('watermark');
        // Crop
        $this->c = (bool)$this->params->get('crop');
        // Background
        $this->bg = empty($this->params->get('bg')) ? null : $this->params->get('bg');

        $this->path = ($this->params->get('type') == 'local') ? public_path($this->params->get('url')) : file_get_contents($this->params->get('url'));

    }

    /**
     * @param mixed $selectedPreset
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
            $this->params->put('w', config('sz4h-image.default.w', null));
        }
        if (!$this->params->has('h')) {
            $this->params->put('h', config('sz4h-image.default.h', null));
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

    /**
     * @param ImageInterface $image
     * @return void
     */
    protected function watermark(ImageInterface $image): ImageInterface
    {
        try {
            $watermarkImage = Image::read(config('sz4h-image.watermark.image'));
        } catch (NotFoundException|Exception $e) {
            $watermarkImage = Image::read(config('sz4h-image.not_found_image_path'));
        }
        $wmWidth = $this->h * config('sz4h-image.watermark.ratio');
        $wmHeight = $this->w * config('sz4h-image.watermark.ratio');
        $wmHeight = $wmHeight ? $wmHeight : null;
        $wmWidth = $wmWidth ? $wmWidth : null;
        $watermarkImage->scale(
            $wmWidth,
            $wmHeight,
        );
        $image = $image->place(
            $watermarkImage,
            config('sz4h-image.watermark.position'),
            config('sz4h-image.watermark.offsetX'),
            config('sz4h-image.watermark.offsetY')
        );
        return $image;
    }
}
