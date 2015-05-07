Slash / A simple Web Framework
=============================

Slash is a PHP micro-framework to develop websites:

.. code-block:: php

    <?php

    require_once __DIR__.'/vendor/autoload.php';

    $app = new \Slash\Slash(include 'config.php', [
    	new \Slash\Module\Impl\PDOModule(),
    	new \Slash\Module\Impl\RedisModule()
    ]);

    $app->get('/', function() use($app) {
    	return 'Hello World';
    });

    $app->run();


License
-------

Slash is licensed under the MIT license.
