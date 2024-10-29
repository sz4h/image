<?php

use Illuminate\Support\Facades\Route;
use Space\Image\Http\Controllers\ImageController;

Route::get(config('sz4h-image.route.prefix') . '/{url?}', [ImageController::class, 'resize'])
	->where('url', '.*')
	->domain(config('sz4h-image.route.domain'))
	->name(config('sz4h-image.route.name'))
	->middleware(config('sz4h-image.route.middlewares'));
