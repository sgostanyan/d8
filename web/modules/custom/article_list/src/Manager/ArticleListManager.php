<?php

namespace Drupal\article_list\Manager;

use Drupal\article_list\Gateway\ArticleListGateway;
use Drupal\Core\Entity\EntityRepository;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class ArticleListManager {

  protected $entityTypeManager;

  protected $articleListGateway;

  protected $entityRepository;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, EntityRepository $entityRepository, ArticleListGateway $articleListGateway) {
    $this->entityTypeManager = $entityTypeManager;
    $this->articleListGateway = $articleListGateway;
    $this->entityRepository = $entityRepository;
  }

  public function getResults() {
    $nids = $this->articleListGateway->fetchResults();
  /*  if (!empty($nids)) {
      $entities = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
      if (!empty($entities)) {
        foreach ($entities as $key => $entity) {
          $entities[$key] = $this->entityRepository->getTranslationFromContext($entity);
        }
        return $this->entityTypeManager->getViewBuilder('node')->viewMultiple($entities, 'teaser');
      }
    }*/
    return ['#markup' => 12];
  }

}
