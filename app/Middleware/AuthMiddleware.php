<?php

namespace App\Middleware;

class AuthMiddleware extends Middleware
{
  public function __invoke($req, $res, $next)
  {
    if (!$this->container->auth->check()) {
      $this->container->flash->addMessage('error', 'Please sign in before doing that.');
      return $res->withRedirect($this->container->router->pathFor('auth.signin'));
    }

    $res = $next($req, $res);
    return $res;
  }
}