<?php


namespace Drupal\budge\Manager;

use Drupal\Core\Extension\ModuleHandlerInterface;
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
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * @var string
   */
  protected $filePath;

  /**
   * BudgeExportManager constructor.
   *
   * @param \Drupal\budge\Manager\BudgeManager $budgeManager
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   */
  public function __construct(BudgeManager $budgeManager, ModuleHandlerInterface $moduleHandler) {
    $this->budgeManager = $budgeManager;
    $this->moduleHandler = $moduleHandler;
    $this->filePath = $moduleHandler->getModule('budge')
        ->getPath() . '/data/budge.yml';
  }

  /**
   * @return bool
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function exportBudget() {
    $budget = $this->budgeManager->getBudget();
    $yml = Yaml::dump($budget);
    return $yml ? $this->writeFile($yml) : FALSE;
  }

  /**
   * @param $yml
   *
   * @return bool
   */
  protected function writeFile($yml) {
    return file_put_contents($this->filePath, $yml) ? TRUE : FALSE;
  }

  /**
   * @return mixed|void
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function importBudget() {
    $yml = $this->readFile();
    return $yml ? $this->budgeManager->createBudget($yml) : NULL;
  }

  /**
   * @return mixed
   */
  protected function readFile() {
    return Yaml::parseFile($this->filePath);
  }

  /**
   * @return string
   */
  public function getFilePath() {
    return $this->filePath;
  }

}
