<?php

namespace Drupal\d8_access\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class MainController
 *
 * @package Drupal\d8_access\Controller
 */
class MainController extends ControllerBase {

  /**
   * @return array
   */
  public function page() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Access')
    ];
  }

}
