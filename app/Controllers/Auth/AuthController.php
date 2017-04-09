<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;


class AuthController extends Controller
{
  /**
   * @param $req
   * @param $res
   * @return mixed
   */
  public function getSignUp($req, $res)
  {
    return $this->view->render($res, 'auth/signup.twig');
  }

  /**
   *
   */
  public function postSignUp($req, $res)
  {
   $user =  User::create([
      'email' => $req->getParam('email'),
      'name' => $req->getParam('name'),
      'password' => password_hash( $req->getParam('password'), PASSWORD_DEFAULT ),
    ]);
   return $res->withRedirect($this->router->pathFor('home'));
  }
}