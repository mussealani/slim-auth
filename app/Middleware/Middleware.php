<?php

namespace App\Middleware;

class Middleware
{
  protected $container;

  /**
   * Middleware constructor.
   * @param $container
   */
  public function __construct($container)
  {
    $this->container = $container;
  }
}