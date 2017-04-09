<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

use App\Models\User;

class HomeController extends Controller
{
  /**
   * @param $req
   * @param $res
   * @return mixed
   */
  public function index($req, $res)
  {

    return $this->view->render($res, 'home.twig');
  }
}