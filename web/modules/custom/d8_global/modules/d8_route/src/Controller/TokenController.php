<?php

namespace Drupal\d8_route\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class UserController
 *
 * @package Drupal\d8_route\Controller
 */
class TokenController extends ControllerBase {

  /**
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function csrftoken() {

    return new JsonResponse(['ok']);

  }

}
