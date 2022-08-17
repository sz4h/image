<?php

use Illuminate\Support\Facades\Route;
use Space\Image\Http\Controllers\ImageController;

Route::get(config('image.route.prefix') . '/{url?}', [ImageController::class, 'resize'])
	->where('url', '.*')
	->domain(config('image.route.domain'))
	->name(config('image.route.name'))
	->middleware(config('image.route.middlewares'));
