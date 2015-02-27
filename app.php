<?php
require __DIR__.'/vendor/autoload.php';

$config = [
	'app.environment' => \Slash\Slash::DEV,
	'app.debug' => true,
	'route.caseSensitive' => false,
	'template.path' => __DIR__."/views"
];

$app = new \Slash\Slash($config, []);

$app->get('/', function() use($app) {
	return $app->render('home.html.twig');
});

$app->get('/product/{id}/list', function($id) {
	echo 'Hello Product :). Parameter Id: '.$id;
});

$app->post('/product/{id}/update', function($id) {
	echo 'Hello Product :). Parameter Id: '.$id." - Update";
});

$app->map('/about', function() {
	echo 'Hello Multiple World!';
})->method([\Slash\Http\Request::GET, \Slash\Http\Request::POST]);

$app->run();

