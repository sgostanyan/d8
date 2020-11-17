<?php

namespace Drupal\list_page\Manager;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\list_page\Gateway\ConfigGateway;

/**
 * Class ConfigManager
 *
 * @package Drupal\list_page\Manager
 */
class ConfigManager {

  /**
   * @var \Drupal\list_page\Gateway\ConfigGateway
   */
  protected $configGateway;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTYpeManager;

  /**
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * ConfigManager constructor.
   *
   * @param \Drupal\list_page\Gateway\ConfigGateway $configGateway
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   */
  public function __construct(ConfigGateway $configGateway, EntityTypeManagerInterface $entityTypeManager, EntityFieldManagerInterface $entityFieldManager) {
    $this->configGateway = $configGateway;
    $this->entityTYpeManager = $entityTypeManager;
    $this->entityFieldManager = $entityFieldManager;
  }

  /**
   * @param string $entityTypeId
   * @param string $bundleFieldName
   * @param string $bundleId
   *
   * @return mixed|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getConfigEntityId(string $entityTypeId, string $bundleFieldName, string $bundleId) {
    $id = $this->configGateway->fetchConfigEntityId($entityTypeId, $bundleFieldName, $bundleId);
    return !empty($id) ? reset($id) : NULL;
  }

  /**
   * @param string $entityTypeId
   * @param string $bundleFieldName
   * @param string $bundleId
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getConfigEntity(string $entityTypeId, string $bundleFieldName, string $bundleId) {
    $id = $this->getConfigEntityId($entityTypeId, $bundleFieldName, $bundleId);
    return $id ? $this->entityTYpeManager->getStorage($entityTypeId)->load($id) : NULL;
  }

  /**
   * @param $entityTypeId
   * @param $bundleFieldName
   * @param $bundleId
   * @param $language
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getValues($entityTypeId, $bundleFieldName, $bundleId, $language) {
    $entity = $this->getConfigEntity($entityTypeId, $bundleFieldName, $bundleId);
    $entity = $entity->hasTranslation($language) ? $entity->getTranslation($language) : $entity;
    $fields = $this->entityFieldManager->getFieldDefinitions($entityTypeId, $bundleId);
    $values = [];
    if ($entity) {
      foreach ($fields as $name => $field) {
        if(strpos($name, 'field_') !== FALSE) {
          $values[] = $entity->get($name)->getValue();
        }
      }
    }
    return $values;
  }

}
