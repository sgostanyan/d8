<?php

namespace Drupal\budge\Gateway;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class BudgeExportGateway
 *
 * @package Drupal\budge\Gateway
 */
class BudgeGateway {

  const PARAGRAPH_FIELDS = [
    'field_credits' => 'credit',
    'field_monthly_expenses' => 'monthly_expense',
    'field_ponctual_expenses' => 'ponctual_expense',
  ];

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * @return \Drupal\Core\Entity\EntityInterface|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function fetchBudgeEntity() {
    $bid = $this->fetchBudge();
    return !empty($bid) ? $this->entityTypeManager->getStorage('node')
      ->load(reset($bid)) : NULL;
  }

  /**
   * @return array|int
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function fetchBudge() {
    return $this->entityTypeManager->getStorage('node')
      ->getQuery()
      ->condition('type', 'budget')
      ->range(0, 1)
      ->execute();
  }

  /**
   * @param $pids
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function fetchParagraphEntities($pids) {
    $list = [];
    foreach ($pids as $key => $pid) {
      $list[] = isset($pid['target_id']) ? $pid['target_id'] : $pid;
    }
    return $this->entityTypeManager->getStorage('paragraph')
      ->loadMultiple($list);
  }

  /**
   * @param $values
   *
   * @return array|int
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function addBudgetEntity() {
    $entityStorage = $this->entityTypeManager->getStorage('node');
    $budgeId = $this->fetchBudge();
    if (empty($budgeId)) {
      $entity = $entityStorage->create([
        'type' => 'budget',
        'title' => 'Budget',
      ]);
      $entity->save();
      $budgeId = $entity->id();
    }
    return is_array($budgeId) ? reset($budgeId) : $budgeId;
  }

  /**
   * @param $values
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function addParagraphEntity($values) {
    $entityStorage = $this->entityTypeManager->getStorage('paragraph');
    $ids = [];
    foreach ($values as $value) {
      $type = self::PARAGRAPH_FIELDS[$value['type']];
      unset($value['type']);
      $data = array_merge(['type' => $type], $value);
      $entity = $entityStorage->create($data);
      $entity->save();
      $ids[]['target_id'] = $entity->id();
    }
    return $ids;
  }

  /**
   * @param $bid
   * @param $budgetFields
   *
   * @return int
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function attachParagraphToBudget($bid, $budgetFields) {
    $budgetEntity = $this->entityTypeManager->getStorage('node')->load($bid);
    if ($budgetEntity) {
      foreach ($budgetFields as $field => $values) {
        $top='';
        $budgetEntity->set('field_credits', ['target_id' => '13', 'target_id' => '399']);
      }
      return $budgetEntity->save();
    }
    return NULL;
  }

}
