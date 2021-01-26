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
    if (!empty($nids)) {
      $entities = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
      if (!empty($entities)) {
        foreach ($entities as $key => $entity) {
          $entities[$key] = $this->entityRepository->getTranslationFromContext($entity);
        }
        $results = $this->entityTypeManager->getViewBuilder('node')->viewMultiple($entities, 'teaser');

        /* Managing caches. */
        // Adding a cache context to results depending url query args.
        $results['#cache']['contexts'][] = 'url.query_args';
        // Adding a custom cache tag to results, which must be invalidated when a node Article will be created or updated.
        $results['#cache']['tags'][] = 'article_list_results';

        return $results;
      }
    }
    return [];
  }

}
