<?php

namespace Drupal\article_list\Manager;

use Drupal\article_list\Gateway\ArticleListGateway;
use Drupal\Core\Entity\EntityRepository;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ArticleListManager
 *
 * @package Drupal\article_list\Manager
 */
class ArticleListManager {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\article_list\Gateway\ArticleListGateway
   */
  protected $articleListGateway;

  /**
   * @var \Drupal\Core\Entity\EntityRepository
   */
  protected $entityRepository;

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * ArticleListManager constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Entity\EntityRepository $entityRepository
   * @param \Drupal\article_list\Gateway\ArticleListGateway $articleListGateway
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, EntityRepository $entityRepository, ArticleListGateway $articleListGateway, RequestStack $requestStack) {
    $this->entityTypeManager = $entityTypeManager;
    $this->articleListGateway = $articleListGateway;
    $this->entityRepository = $entityRepository;
    $this->requestStack = $requestStack;
  }

  /**
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getResults() {

    // URL query params.
    $type = $this->requestStack->getCurrentRequest()->get('type');
    $country = $this->requestStack->getCurrentRequest()->get('country');

    // Preparing node conditions
    $conditions = [];
    // Check if value is not null and is numeric.
    !empty($type) && is_numeric($type) ? $conditions['type'] = $type : '';
    !empty($country) && is_numeric($country) ? $conditions['country'] = $country : '';

    // Getting node ids.
    $nids = $this->articleListGateway->fetchResults($conditions);

    // If results.
    if (!empty($nids)) {

      // loading entities.
      $entities = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);

      // If entities loaded.
      if (!empty($entities)) {
        foreach ($entities as $key => $entity) {
          // Load entities from translation context.
          $entities[$key] = $this->entityRepository->getTranslationFromContext($entity);
        }
        // Building node's view.
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
