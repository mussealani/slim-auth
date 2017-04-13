<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;


class PasswordController extends Controller
{
  /**
   * @param $req
   * @param $res
   */
  public function getChangePassword($req, $res)
  {
    $this->view->render($res, 'auth/password/change.twig');
  }

  /**
   * @param $req
   * @param $res
   * @return mixed
   */
  public function postChangePassword($req, $res)
  {
    $validation = $this->validator->validate($req, [
      'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
      'password' => v::noWhitespace()->notEmpty(),
    ]);

    if ($validation->failed()) {

      return $res->withRedirect($this->router->pathFor('auth.password.change'));
    }
    $this->auth->user()->setPassword($req->getParam('password'));
    $this->flash->addMessage('info', 'Your password has been changed');
    return $res->withRedirect($this->router->pathFor('home'));
  }
}