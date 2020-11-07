<?php


namespace Drupal\budge\Manager;

use Drupal\budge\Gateway\BudgeGateway;
use Drupal\Core\Extension\ModuleHandlerInterface;

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
    'status',
  ];

  const ICONS = [
    'field_credits' => 'circle-cropped-blue.png',
    'field_monthly_expenses' => 'circle-cropped-purple.png',
    'field_ponctual_expenses' => 'circle-cropped-yellow.png',
  ];

  /**
   * @var \Drupal\budge\Gateway\BudgeGateway
   */
  protected $budgeGateway;

  /**
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * @var string
   */
  protected $modulePath;

  /**
   * BudgeExportManager constructor.
   *
   * @param \Drupal\budge\Gateway\BudgeGateway $budgeGateway
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   */
  public function __construct(BudgeGateway $budgeGateway, ModuleHandlerInterface $moduleHandler) {
    $this->budgeGateway = $budgeGateway;
    $this->moduleHandler = $moduleHandler;
    $this->modulePath = $moduleHandler->getModule('budge')->getPath();
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
    $total = $this->sortByDate($list);

    $filtered = [];
    $amount = $list['field_start_amount'];
    $totalPonctualExpenses = 0;
    $totalMonthlyExpenses = 0;
    $totalCredits = 0;

    // Calculating and sanitizing.
    foreach ($total as $key => $item) {
      $type = $item['type'];
      if ($type == "field_credits") {
        $totalCredits += $item['field_amount'];
        if (!empty($item['status'])) {
          $amount += $item['field_amount'];
          $filtered[] = [
            'Titre' => $item['field_title'],
            'Type' => 'Ajout',
            'Date' => $item['field_date'],
            'Montant' => '+' . number_format($item['field_amount'], 2, ',', ''),
            'Solde' => number_format($amount, 2, ',', ''),
            'icon' => $this->modulePath . '/images/' . self::ICONS[$type],
          ];
        }
      }
      elseif ($type == "field_ponctual_expenses") {
        $totalPonctualExpenses += $item['field_amount'];
        if (!empty($item['status'])) {
          $amount -= $item['field_amount'];
          $filtered[] = [
            'Titre' => $item['field_title'],
            'Type' => 'Dépense ponctuelle',
            'Date' => $item['field_date'],
            'Montant' => '-' . number_format($item['field_amount'], 2, ',', ''),
            'Solde' => number_format($amount, 2, ',', ''),
            'icon' => $this->modulePath . '/images/' . self::ICONS[$type],
          ];
        }
      }
      elseif ($type == "field_monthly_expenses") {
        $totalMonthlyExpenses += $item['field_amount'];
        if (!empty($item['status'])) {
          $amount -= $item['field_amount'];
          $filtered[] = [
            'Titre' => $item['field_title'],
            'Type' => 'Dépense mensuelle',
            'Date' => $item['field_date'],
            'Montant' => '-' . number_format($item['field_amount'], 2, ',', ''),
            'Solde' => number_format($amount, 2, ',', ''),
            'icon' => $this->modulePath . '/images/' . self::ICONS[$type],
          ];
        }
      }
    }

    return [
      'list' => $list,
      'sorted' => array_reverse($filtered),
      'currentAmount' => number_format($amount, 2, ',', ''),
      'expectedAmount' => number_format($list['field_start_amount'] - $totalMonthlyExpenses - $totalPonctualExpenses + $totalCredits,
        2,
        ',',
        ''),
      'expenses' =>  $expenses = ['monthly' => $totalMonthlyExpenses, 'ponctual' => $totalPonctualExpenses],
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

  /**
   * @param $lists
   *
   * @return array
   */
  protected function sortByDate($lists) {
    $output = [];
    $sorted = [];
    foreach ($lists as $type => $list) {
      if (!is_array($list)) {
        continue;
      }
      foreach ($list as $item) {
        $sorted[str_replace('-', '', $item['field_date'])][] = $item;
      }
    }
    ksort($sorted);
    foreach ($sorted as $item) {
      $output = array_merge($output, $item);
    }
    return $output;
  }

}
