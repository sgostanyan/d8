<?php

namespace Drupal\article_list\Gateway;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;

/**
 * Class ArticleListGateway
 *
 * @package Drupal\article_list\Gateway
 */
class ArticleListGateway {

  const PAGER = 2;

  /**
   * @var \Drupal\Core\Entity\Query\QueryInterface
   */
  protected $query;

  /**
   * ArticleListGateway constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->query = $entityTypeManager->getStorage('node')->getQuery()->condition('type', 'article')->condition('status', NodeInterface::PUBLISHED);
  }

  /**
   * @param array $conditions
   *
   * @return array|int
   */
  public function fetchResults(array $conditions) {

    // Preparing query.
    foreach ($conditions as $key => $value) {
      if (!empty($value)) {
        switch ($key) {
          case 'type' :
            $this->query->condition('field_type', $value);
            break;
          case 'country' :
            $this->query->condition('field_country', $value);
            break;
          case 'exclude' :
            $this->query->condition('nid', $value, '!=');
            break;
        }
      }
    }
    // Adding pager.
    $this->query->pager(self::PAGER);
    return $this->query->execute();
  }

  /**
   * @return array|int
   */
  public function fetchLastNews() {
    return $this->query->sort('created', 'DESC')->range(0, 1)->execute();

  }

}
