<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;


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
    $validation = $this->validator->validate($req, [
      'email' => v::noWhitespace()->notEmpty(),
      'name' => v::notEmpty()->alpha(),
      'password' => v::noWhitespace()->notEmpty(),
    ]);

    if ($validation->failed()) {
      return $res->withRedirect($this->router->pathFor('auth.signup'));
    }

   $user =  User::create([
      'email' => $req->getParam('email'),
      'name' => $req->getParam('name'),
      'password' => password_hash( $req->getParam('password'), PASSWORD_DEFAULT ),
    ]);
   return $res->withRedirect($this->router->pathFor('home'));
  }
}