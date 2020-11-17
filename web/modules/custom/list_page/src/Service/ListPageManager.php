<?php

namespace Drupal\list_page\Service;

use Drupal\Core\Language\LanguageManager;
use Drupal\list_page\Manager\ConfigManager;
use Drupal\list_page\Manager\ContentManager;

/**
 * Class ListPageManager
 *
 * @package Drupal\list_page
 */
class ListPageManager {

  /**
   * @var \Drupal\list_page\Manager\ConfigManager
   */
  protected $configManager;

  /**
   * @var \Drupal\Core\Language\LanguageManager
   */
  protected $languageManager;

  /**
   * @var int
   */
  protected $listPageId;

  /**
   * @var string
   */
  protected $language;

  /**
   * @var array
   */
  protected $configValues;

  /**
   * @var \Drupal\list_page\Manager\ContentManager
   */
  protected $contentManager;

  /**
   * @var int
   */
  protected $totalResults;

  /**
   * ListPageManager constructor.
   *
   * @param \Drupal\list_page\Manager\ConfigManager $configManager
   * @param \Drupal\Core\Language\LanguageManager $languageManager
   * @param \Drupal\list_page\Manager\ContentManager $contentManager
   */
  public function __construct(ConfigManager $configManager, LanguageManager $languageManager, ContentManager $contentManager) {
    $this->configManager = $configManager;
    $this->languageManager = $languageManager;
    $this->language = $languageManager->getCurrentLanguage()->getId();
    $this->contentManager = $contentManager;
  }

  /**
   * @param string $configEntityTypeId
   * @param string $configBundleFieldName
   * @param string $configBundleId
   * @param string|null $language
   *
   * @return $this|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function create(string $configEntityTypeId, string $configBundleFieldName, string $configBundleId, string $language = NULL) {
    $id = $this->configManager->getConfigEntityId($configEntityTypeId, $configBundleFieldName, $configBundleId);
    if ($id) {
      $this->listPageId = $id;
      $this->language = $language ? $language : $this->language;
      $this->configValues = $this->configManager->getValues($configEntityTypeId, $configBundleFieldName, $configBundleId, $language);
      return $this;
    }
    return NULL;
  }

  /**
   * @return string
   */
  public function getLanguage() {
    return $this->language;
  }

  /**
   * @return int
   */
  public function getListPageId() {
    return $this->listPageId;
  }

  /**
   * @return array
   */
  public function getConfigValues() {
    return $this->configValues;
  }

  public function getResults($entityTypeId, $conditions, $pager = NULL) {
    $results = $this->contentManager->getContent($entityTypeId, $pager, $conditions);
    $this->totalResults = $results['count'];
    return $results['results'];
  }

  /**
   * @return int
   */
  public function getTotalResults() {
    return $this->totalResults;
  }

}
