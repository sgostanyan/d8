<?php

namespace Drupal\d8_route\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class MainController.
 */
class MainController extends ControllerBase {

  /**
   * @return array
   */
  public function page() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('<ul>
<li><a href="/admin/route/1">User</a></li>
<li><a href="/admin/route/csrftoken?token=' . \Drupal::csrfToken()->get('admin/route/csrftoken') . '">Token</a></li>
</ul>'
      ),
    ];
  }

}
