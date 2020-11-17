<?php

namespace Drupal\list_page\Gateway;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class ConfigGateway
 *
 * @package Drupal\list_page\Gateway
 */
class ConfigGateway {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * ConfigGateway constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * @param string $entityTypeId
   * @param string $bundleFieldName
   * @param string $bundleId
   *
   * @return array|int
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function fetchConfigEntityId(string $entityTypeId, string $bundleFieldName, string $bundleId) {
    $query = $this->entityTypeManager->getStorage($entityTypeId)->getQuery()->condition($bundleFieldName, $bundleId);
    if ($query->exists('status')) {
      $query->condition('status', 1);
    }
    $query->sort('changed', 'DESC');
    $query->range(0, 1);
    return $query->execute();
  }

}
