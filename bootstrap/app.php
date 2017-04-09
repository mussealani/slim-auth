<?php

session_start();


use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use App\Controllers\HomeController;
use App\Controllers\Auth\AuthController;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';

$app = new App([
  'settings' => [
    'displayErrorDetails' => true,
    'db' => [
      'driver' => 'mysql',
      'host' => 'localhost',
      'database' => 'slim-auth',
      'username' => 'root',
      'password' => '',
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ]
  ]
]);

$container = $app->getContainer();

// Laravel Illuminate database
$capsule = new Capsule;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

/**
 * @param $container
 * @return Capsule
 */
$container['db'] = function ($container) use ($capsule) {
  return $capsule;
};

/**
 * @param $container
 * @return Twig
 */
$container['view'] = function ($container) {
  $view = new Twig(__DIR__ . '/../resources/views', [
    'cache' => false,
  ]);

  $view->addExtension(new TwigExtension(
    $container->router,
    $container->request->getUri()
  ));

  $view->addExtension(new Twig_Extension_Debug());

  return $view;
};

$container['HomeController'] = function ($container) {
  return new HomeController($container);
};

$container['AuthController'] = function ($container) {
  return new AuthController($container);
};



require __DIR__ . '/../app/routes.php';