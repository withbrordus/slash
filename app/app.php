<?php

require __DIR__.'/../vendor/autoload.php';
include __DIR__.'/controllers/BlogController.php';

$app = new \Slash\Slash(include 'config.php', [
	new \Slash\Module\Impl\PDOModule(),
	new \Slash\Module\Impl\RedisModule()
]);

$app->rootRoute('/blog', new BlogController());

$app->get('/', function() use($app) {
	return $app->render('welcome.html.twig');
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

