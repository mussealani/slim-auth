<?php
namespace App\Middleware;

class OldInputMiddleware extends Middleware
{
  public function __invoke($req, $res, $next)
  {
    if (isset($_SESSION['old'])) {
      $this->container->view->getEnvironment()->addGlobal( 'old', $_SESSION['old'] );
      $_SESSION['old'] = $req->getParams();
    }

    $res = $next($req, $res);
    return $res;
  }
}