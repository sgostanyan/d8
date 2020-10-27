<?php


namespace Drupal\budge\Manager;

use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BudgeExportManager
 *
 * @package Drupal\budge
 */
class BudgeExportManager {

  /**
   * @var \Drupal\budge\Manager\BudgeManager
   */
  protected $budgeManager;

  /**
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * BudgeExportManager constructor.
   *
   * @param \Drupal\budge\Manager\BudgeManager $budgeManager
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   */
  public function __construct(BudgeManager $budgeManager, FileSystemInterface $fileSystem) {
    $this->budgeManager = $budgeManager;
    $this->fileSystem = $fileSystem;
  }

  /**
   * @return bool
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function exportBudget() {
    $budget = $this->budgeManager->getBudget();
    $yml = !empty($budget) ? Yaml::dump($budget) : NULL;
    return $yml ? $this->writeFile($yml) : FALSE;
  }

  /**
   * @param $yml
   *
   * @return false|string
   */
  protected function writeFile($yml) {
    return $this->fileSystem->saveData($yml,
      'private://budge/export/budge.yml') ? 'private://budge/export/budge.yml' : FALSE;
  }

  /**
   * @param $fileUri
   *
   * @return int|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function importBudget($fileUri) {
    $yml = $this->readFile($fileUri);
    return $yml ? $this->budgeManager->createBudget($yml) : NULL;
  }

  /**
   * @param $fileUri
   *
   * @return mixed
   */
  protected function readFile($fileUri) {
    return Yaml::parseFile($fileUri);
  }

}
