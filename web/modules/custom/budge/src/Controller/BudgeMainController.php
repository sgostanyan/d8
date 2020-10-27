<?php

namespace Drupal\budge\Controller;

use Drupal\budge\Manager\BudgeExportManager;
use Drupal\budge\Manager\BudgeManager;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class BudgeMainController.
 */
class BudgeMainController extends ControllerBase {

  /**
   * @var \Drupal\budge\Manager\BudgeManager
   */
  protected $budgetManager;

  /**
   * @var \Drupal\budge\Manager\BudgeExportManager
   */
  protected $budgetExportManager;

  /**
   * BudgeMainController constructor.
   *
   * @param \Drupal\budge\Manager\BudgeManager $budgetManager
   * @param \Drupal\budge\Manager\BudgeExportManager $budgeExportManager
   */
  public function __construct(BudgeManager $budgetManager, BudgeExportManager $budgeExportManager) {
    $this->budgetManager = $budgetManager;
    $this->budgetExportManager = $budgeExportManager;
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

  /**
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function edit() {
    $budgetEntity = $this->budgetManager->getBudgetEntity();
    if ($budgetEntity) {
      return new RedirectResponse('/node/' . $budgetEntity->id() . '/edit?destination=/budge');
    }
  }

}
