<?php

namespace Drupal\list_page\Manager;

use Drupal\list_page\Gateway\ContentGateway;

/**
 * Class ContentManager
 *
 * @package Drupal\list_page\Manager
 */
class ContentManager {

  /**
   * @var \Drupal\list_page\Gateway\ContentGateway
   */
  protected $contentGateway;

  /**
   * ContentManager constructor.
   *
   * @param \Drupal\list_page\Gateway\ContentGateway $contentGateway
   */
  public function __construct(ContentGateway $contentGateway) {
    $this->contentGateway = $contentGateway;
  }

  /**
   * @param $entityTypeId
   * @param $pager
   * @param array $conditions
   *
   * @return array
   */
  public function getContent($entityTypeId, $pager, $conditions = ['status', 1]) {
    $results = $this->contentGateway->fetchContent($entityTypeId, $conditions, $pager);
    return [
      'count' => count($results),
      'results' => $results,
    ];
  }

}
