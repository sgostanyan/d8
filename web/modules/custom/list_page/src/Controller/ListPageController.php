<?php

namespace Drupal\list_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\list_page\ListPageInterface;
use Drupal\list_page\Service\ListPageManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ListPageController
 *
 * @package Drupal\list_page\Controller
 */
class ListPageController extends ControllerBase implements ListPageInterface {

  /**
   * @var \Drupal\list_page\Service\ListPageManager
   */
  protected $listPageManager;

  /**
   * PageController constructor.
   *
   * @param \Drupal\list_page\Service\ListPageManager $listPageManager
   */
  public function __construct(ListPageManager $listPageManager) {
    $this->listPageManager = $listPageManager;
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return mixed|void
   */
  public function page(Request $request) {
  }

  /**
   * @param array|null $conditions
   */
  public function buildConditions(array $conditions = []) {
  }

  /**
   * @return \Drupal\list_page\Service\ListPageManager
   */
  protected function listPageManager() {
    return $this->listPageManager;
  }
}
