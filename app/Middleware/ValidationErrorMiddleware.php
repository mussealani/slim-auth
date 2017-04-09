<?php

namespace App\Middleware;

class ValidationErrorMiddleware extends Middleware
{
  public function __invoke($req, $res, $next)
  {
    if (isset($_SESSION['errors'])) {
      $this->container->view->getEnvironment()->addGlobal( 'errors', $_SESSION['errors'] );
      unset($_SESSION['errors']);
    }

    $res = $next($req, $res);
    return $res;
  }
}