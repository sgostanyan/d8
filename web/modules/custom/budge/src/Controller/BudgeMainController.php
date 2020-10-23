<?php

namespace Drupal\budge\Controller;

use Drupal\budge\Manager\BudgeManager;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class BudgeMainController.
 */
class BudgeMainController extends ControllerBase {

  /**
   * @var \Drupal\budge\Manager\BudgeManager
   */
  protected $budgetManager;

  /**
   * BudgeMainController constructor.
   *
   * @param \Drupal\budge\Manager\BudgeManager $budgetManager
   */
  public function __construct(BudgeManager $budgetManager) {
    $this->budgetManager = $budgetManager;
  }

  /**
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function main() {

    return [
      '#theme' => 'budge_main',
      '#content' => $this->budgetManager->getBudget(),
      '#attached' => [
        'library' => 'budge/budge',
      ],
    ];
  }

}
