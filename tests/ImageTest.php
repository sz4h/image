<?php


use Orchestra\Testbench\Concerns\Testing;


beforeEach()->createApplication();


it('has route', function () {
	$route = route(config('image.route.name'));
	expect($route)->toBeString();
});
