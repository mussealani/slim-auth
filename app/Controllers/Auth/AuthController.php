<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;


class AuthController extends Controller
{

  public function getSignOut($req, $res)
  {
    $this->auth->logout();
    return $res->withRedirect($this->router->pathFor('home'));
  }

  /**
   * @param $req
   * @param $res
   * @return mixed
   */
  public function getSignIn($req, $res)
  {
    return $this->view->render($res, 'auth/signin.twig');
  }

  public function postSignIn($req, $res)
  {
    $auth = $this->auth->attempt(
      $req->getParam('email'),
      $req->getParam('password')
    );

    if (!$auth) {
      $this->flash->addMessage('error', 'Could not sign you in with those details!');
      return $res->withRedirect($this->router->pathFor('auth.signin'));
    }

    $this->flash->addMessage('info', 'You have been signed in!');

    return $res->withRedirect($this->router->pathFor('home'));
  }

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
   * @param $req
   * @param $res
   * @return mixed
   */
  public function postSignUp($req, $res)
  {
    $validation = $this->validator->validate($req, [
      'email' => v::noWhitespace()->notEmpty()->emailAvailable(),
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

    $this->flash->addMessage('info', 'You have been signed up!');

    $this->auth->attempt($user->email, $req->getParam('password'));
   return $res->withRedirect($this->router->pathFor('home'));
  }
}