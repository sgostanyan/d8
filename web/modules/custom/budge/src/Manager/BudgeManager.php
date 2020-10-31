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
    'field_start_amount',
    'field_credits',
    'field_monthly_expenses',
    'field_ponctual_expenses',
  ];

  const FIELDS_PARAGRAPHS = [
    'field_date',
    'field_amount',
    'field_title',
    'status'
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
  public function getBudgets() {
    $budgetEntities = $this->getBudgetEntity();
    $budgets = [];
    if ($budgetEntities) {
      foreach ($budgetEntities as $budgetEntity) {
        $list['title'] = $budgetEntity->label();
        foreach (self::FIELDS_BUDGET as $field) {
          if ($budgetEntity->hasField($field)) {
            if ($field !== 'field_start_amount') {
              $list[$field] = $this->manageParagraphFields($field,
                $budgetEntity->get($field)->getValue());
            }
            else {
              $list[$field] = $budgetEntity->get($field)
                ->getValue()[0]['value'];
            }
          }
        }
        $budgets[$budgetEntity->id()] = $this->sortList($list);
      }
    }
    return !empty($budgets) ? $budgets : NULL;
  }

  /**
   * @return \Drupal\Core\Entity\EntityInterface[]|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getBudgetEntity() {
    return $this->budgeGateway->fetchBudgetEntities();
  }

  /**
   * @param $pids
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function manageParagraphFields($type, $pids) {
    $paragraphEntities = $this->budgeGateway->fetchParagraphEntities($pids);
    $output = [];
    if (!empty($paragraphEntities)) {
      foreach ($paragraphEntities as $paragraphEntity) {
        $list = [];
        foreach (self::FIELDS_PARAGRAPHS as $field) {
          if ($paragraphEntity->hasField($field)) {
            $list[$field] = $paragraphEntity->get($field)
              ->getValue()[0]['value'];
            $list['type'] = $type;
          }
        }
        $output[] = $list;
      }
    }
    return $output;
  }

  /**
   * @param array $list
   *
   * @return array[]
   */
  protected function sortList(array $list) {
    $total = array_merge($list['field_monthly_expenses'],
      $list['field_ponctual_expenses'],
      $list['field_credits']);
    $sorted = [];
    $amount = $list['field_start_amount'];
    $totalPonctualExpenses = 0;
    $totalMonthlyExpenses = 0;
    $expenses = ['monthly' => 0, 'ponctual' => 0];

    foreach ($total as $key => $item) {
      $type = $item['type'];
      if ($type == "field_credits") {
        $amount += !empty($item['status']) ? $item['field_amount'] : 0;
        $sorted[] = [
          'Titre' => $item['field_title'],
          'Type' => 'Ajout',
          'Date' => $item['field_date'],
          'Montant' => '+' . number_format($item['field_amount'], 2, ',', ''),
          'Solde' => number_format($amount, 2, ',', ''),
          'afficher' => $item['status'],
        ];
      }
      elseif ($type == "field_monthly_expenses") {
        $amount -= !empty($item['status']) ? $item['field_amount'] : 0;
        $totalMonthlyExpenses += $item['field_amount'];
        $expenses['monthly'] = number_format($totalMonthlyExpenses, 2, ',', '');
        $sorted[] = [
          'Titre' => $item['field_title'],
          'Type' => 'Dépense mensuelle',
          'Date' => $item['field_date'],
          'Montant' => '-' . number_format($item['field_amount'], 2, ',', ''),
          'Solde' => number_format($amount, 2, ',', ''),
          'afficher' => $item['status'],
        ];
      }
      elseif ($type == "field_ponctual_expenses") {
        $amount -= !empty($item['status']) ? $item['field_amount'] : 0;
        $totalPonctualExpenses += $item['field_amount'];
        $expenses['ponctual'] = number_format($totalPonctualExpenses,
          2,
          ',',
          '');
        $sorted[] = [
          'Titre' => $item['field_title'],
          'Type' => 'Dépense ponctuelle',
          'Date' => $item['field_date'],
          'Montant' => '-' . number_format($item['field_amount'], 2, ',', ''),
          'Solde' => number_format($amount, 2, ',', ''),
          'afficher' => $item['status'],
        ];
      }

    }
    return [
      'list' => $list,
      'sorted' => $sorted,
      'currentAmount' => number_format($amount, 2, ',', ''),
      'expectedAmount' => number_format($amount - $expenses['monthly'] - $expenses['ponctual'], 2, ',', ''),
      'expenses' => $expenses,
    ];
  }

  /**
   * @param $budgets
   *
   * @return int
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function createBudget($budgets) {
    $this->budgeGateway->deleteAllBudgets();
    foreach ($budgets as $values) {
      // Node budget.
      $bid = $this->budgeGateway->addBudgetEntity($values['list']['title']);
      $budgetFields = [];
      $startAmount = $values['list']['field_start_amount'];
      // Paragraphs.
      foreach ($values['list'] as $index => $value) {
        $exceptValues = ['field_start_amount', 'title'];
        if (!in_array($index, $exceptValues)) {
          $budgetFields[$index] = $this->budgeGateway->addParagraphEntity($value);
        }
      }
      $budgetFields['field_start_amount'] = $startAmount;
      if (!$this->budgeGateway->attachParagraphToBudget($bid, $budgetFields)) {
        return NULL;
      }
    }
    return TRUE;
  }

}
