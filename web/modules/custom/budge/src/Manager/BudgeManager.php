<?php


namespace Drupal\budge\Manager;

use Drupal\budge\Gateway\BudgeGateway;

/**
 * Class BudgeManager
 *
 * @package Drupal\budge
 */
class BudgeManager {

  const FIELDS_BUDGET = [
    'field_credits',
    'field_monthly_expenses',
    'field_ponctual_expenses',
  ];

  const FIELDS_PARAGRAPHS = [
    'field_date',
    'field_amount',
    'field_title',
  ];

  /**
   * @var \Drupal\budge\Gateway\BudgeGateway
   */
  protected $budgeGateway;

  /**
   * BudgeExportManager constructor.
   *
   * @param \Drupal\budge\Gateway\BudgeGateway $budgeGateway
   */
  public function __construct(BudgeGateway $budgeGateway) {
    $this->budgeGateway = $budgeGateway;
  }

  /**
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getBudget() {
    $budgetEntity = $this->getBudgetEntity();
    if ($budgetEntity) {
      $list = [];
      foreach (self::FIELDS_BUDGET as $field) {
        if ($budgetEntity->hasField($field)) {
          $list[$field] = $this->manageParagraphFields($budgetEntity->get($field)->getValue());
        }
      }
    }
    return isset($list) ? $list : [];
  }

  /**
   * @return \Drupal\Core\Entity\EntityInterface|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getBudgetEntity() {
    return $this->budgeGateway->fetchBudgeEntity();
  }

  /**
   * @param $pids
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function manageParagraphFields($pids) {
    $paragraphEntities = $this->budgeGateway->fetchParagraphEntities($pids);
    $output = [];
    if (!empty($paragraphEntities)) {
      foreach ($paragraphEntities as $paragraphEntity) {
        $list = [];
        foreach (self::FIELDS_PARAGRAPHS as $field) {
          if ($paragraphEntity->hasField($field)) {
            $list[$field] = $paragraphEntity->get($field)->getValue()[0]['value'];
          }
        }
        $output[] = $list;
      }
    }
    return $output;
  }

}
