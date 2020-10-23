<?php


namespace Drupal\budge\Gateway;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class BudgeExportGateway
 *
 * @package Drupal\budge\Gateway
 */
class BudgeGateway {

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
   * @return array|int
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function fetchBudge() {
    return $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'budget')
      ->range(0, 1)
      ->execute();
  }

  /**
   * @return \Drupal\Core\Entity\EntityInterface|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function fetchBudgeEntity() {
    $bid = $this->fetchBudge();
    return !empty($bid) ? $this->entityTypeManager->getStorage('node')->load(reset($bid)) : NULL;
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
    return $this->entityTypeManager->getStorage('paragraph')->loadMultiple($list);
  }


}
