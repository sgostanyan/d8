<?php

namespace Drupal\article_list\Gateway;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;

class ArticleListGateway {

  protected $query;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->query = $entityTypeManager->getStorage('node')->getQuery()->condition('type', 'article')->condition('status', NodeInterface::PUBLISHED);
  }

  public function fetchResults() {
    return $this->query->execute();
  }

}
