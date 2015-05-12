<?php

class BlogController implements \Slash\ControllerProviderInterface {
	public function connect(\Slash\Slash $app) {
        $app->get('/', function() use($app) {
            return $app->render('panada.html.twig');
        });

        $app->get('/test', function() use($app) {
            return $app->toJSON([
                'test' => 'test'
            ]);
        });
	}
} 