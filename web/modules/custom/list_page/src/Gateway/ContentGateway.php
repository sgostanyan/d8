<?php

namespace Drupal\list_page\Gateway;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class ContentGateway
 *
 * @package Drupal\list_page\Gateway
 */
class ContentGateway {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public function fetchContent($entityTypeId, $conditions, $pager) {
    $query = $this->entityTypeManager->getStorage($entityTypeId)->getQuery();
    foreach ($conditions as $condition) {
      count($condition) == 3 ? $query->condition($condition[0],
        $condition[1],
        $condition[2]) : $query->condition($condition[0], $condition[1]);
    }
    if ($pager !== NULL) {
      $query->pager($pager);
    }
    return $query->execute();

  }

}
