<?php


namespace Drupal\budge\Manager;

use Drupal\Core\Extension\ModuleHandler;
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
   * @var \Drupal\Core\Extension\ModuleHandler
   */
  protected $moduleHandler;

  /**
   * BudgeExportManager constructor.
   *
   * @param \Drupal\budge\Manager\BudgeManager $budgeManager
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   * @param \Drupal\Core\Extension\ModuleHandler $moduleHandler
   */
  public function __construct(BudgeManager $budgeManager, FileSystemInterface $fileSystem, ModuleHandler $moduleHandler) {
    $this->budgeManager = $budgeManager;
    $this->fileSystem = $fileSystem;
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * @return bool
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function exportBudget() {
    $budgets = $this->budgeManager->getBudgets();
    $yml = !empty($budgets) ? Yaml::dump($budgets) : NULL;
    return $yml ? $this->writeFile($yml) : FALSE;
  }

  /**
   * @param $yml
   *
   * @return false|string
   */
  protected function writeFile($yml) {
    $modulePath = \Drupal::moduleHandler()->getModule('budge')->getPath();
    $destinationDirectory = $modulePath . '/data/export/';
    $filePath = $destinationDirectory . 'budge_export.yml';
    $this->fileSystem->prepareDirectory($destinationDirectory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
    return $this->fileSystem->saveData($yml, $filePath, FileSystemInterface::EXISTS_REPLACE) ? $filePath : FALSE;
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
