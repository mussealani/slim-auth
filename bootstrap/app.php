<?php

session_start();
require __DIR__ . '/../vendor/autoload.php';

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use App\Controllers\HomeController;
use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\PasswordController;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Validation\Validator;
use Respect\Validation\Validator as v;

// Load dotenv variables.
$dotenv = new Dotenv\Dotenv('../');
$dotenv->load();


$app = new App([
  'settings' => [
    'determineRouteBeforeAppMiddleware' => true,
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,
    'db' => [
      'driver' => getenv('DRIVER'),
      'host' => getenv('HOST'),
      'database' => getenv('DATABASE'),
      'username' => getenv('USERNAME'),
      'password' => getenv('PASSWORD'),
      'charset' => getenv('CHARESET'),
      'collation' => getenv('COLLATION'),
      'prefix' => getenv('PREFIX'),
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
 * @return \App\Auth\Auth
 */
$container['auth'] = function ($container) {
  return new \App\Auth\Auth;
};

/**
 * @param $container
 * @return \Slim\Flash\Messages
 */
$container['flash'] = function ($container) {
  return new \Slim\Flash\Messages;
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

  $view->getEnvironment()->addGlobal('auth', [
    'check' => $container->auth->check(),
    'user' => $container->auth->user(),
  ]);

  $view->addExtension(new Twig_Extension_Debug());
  $view->getEnvironment()->addGlobal('flash', $container->flash);

  return $view;
};

$container['validator'] = function ($container) {
  return new Validator();
};

$container['HomeController'] = function ($container) {
  return new HomeController($container);
};

$container['AuthController'] = function ($container) {
  return new AuthController($container);
};

$container['PasswordController'] = function ($container) {
  return new PasswordController($container);
};


$container['csrf'] = function ($container) {
  return new \Slim\Csrf\Guard;
};


$app->add(new \App\Middleware\ValidationErrorMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);
v::with('App\\Validation\\Rules\\', true);

require __DIR__ . '/../app/routes.php';