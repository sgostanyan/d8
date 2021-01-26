<?php

namespace Drupal\article_list\Gateway;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class ArticleListGateway {

  protected $query;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->query = $entityTypeManager->getStorage('node')->getQuery()->condition('type', 'article');
  }

  public function fetchResults() {
    return $this->query->execute();
  }

}
