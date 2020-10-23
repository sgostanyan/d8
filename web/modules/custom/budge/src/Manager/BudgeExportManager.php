<?php


namespace Drupal\budge\Manager;

use Drupal\budge\Manager\BudgeManager;

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
   * BudgeExportManager constructor.
   *
   * @param \Drupal\budge\Manager\BudgeManager $budgeManager
   */
  public function __construct(BudgeManager $budgeManager) {
    $this->budgeManager = $budgeManager;
  }

  public function exportBudget() {
   // $budgetEntity = $this->();
   // ksm($budgetEntity);
  }



}
