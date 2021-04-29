<?php

namespace Drupal\d8_permission\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class MainController
 *
 * @package Drupal\d8_permission\Controller
 */
class MainController extends ControllerBase {

  /**
   * @return array
   */
  public function page() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Permission')
    ];
  }

}
