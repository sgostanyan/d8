<?php

namespace Drupal\d8_route\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;

/**
 * Class UserController
 *
 * @package Drupal\d8_route\Controller
 */
class UserController extends ControllerBase {

  /**
   * @return array
   */
  public function page(User $user) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t($user->getAccountName()),
    ];
  }

}
