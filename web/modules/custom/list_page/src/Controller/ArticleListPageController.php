<?php

namespace Drupal\list_page\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleListPageController
 *
 * @package Drupal\list_page\Controller
 */
class ArticleListPageController extends ListPageController {

  // Config entity.
  const CONFIG_ENTITY_TYPE = 'node';

  const CONFIG_BUNDLE_FIELD = 'type';

  const CONFIG_BUNDLE_ID = 'article';

  // Contents to list.
  const CONTENT_ENTITY_TYPE = 'node';

  const CONTENT_BUNDLE_FIELD = 'type';

  const CONTENT_BUNDLE_IDS = ['article'];

  const PAGER = 4; // Number of results per page. Set NULL to disable pager;

  /**
   * @var \Drupal\list_page\Service\ListPageManager
   */
  protected $listPageManager;

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return array|mixed|void
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function page(Request $request) {
    parent::page($request);

    // Initiate list manager.
    $lisPageManager = $this->listPageManager->create(self::CONFIG_ENTITY_TYPE, self::CONFIG_BUNDLE_FIELD, self::CONFIG_BUNDLE_ID);

    // Values from config entity.
    $configValues = $this->listPageManager->getConfigValues();

    // Initiate query parameter is pager is set.
    if (self::PAGER !== NULL && $request->get('page') === NULL) {
      $request->query->set('page', 0);
    }

    // Start search. Use buildConditions() for adding extra conditions.
    $result = $lisPageManager->getResults(self::CONTENT_ENTITY_TYPE, $this->buildConditions(), self::PAGER);

    // Render.
    $render = [];
    $render[] = [
      '#theme' => 'article_list_page',
      '#result' => $result,
      '#type' => 'pager',
    ];
    $render[] = ['#type' => 'pager'];

    return $render;

  }

  /**
   * @param array|null $conditions
   *  Array of array [fieldName, value, operator]
   * ex:
   * [
   *  ['field_category', ['34', '23', '22'], 'IN'],
   *  ['status', '1']
   *  ['changed', 'DESC'],
   * ]
   *
   *
   * @return array|void
   */
  public function buildConditions(array $conditions = []) {
    parent::buildConditions($conditions);
    // Default condition.
    $builtConditions = [
      [
        self::CONFIG_BUNDLE_FIELD,
        self::CONTENT_BUNDLE_IDS,
        'IN',
      ],
    ];
    // Extra conditions.
    foreach ($conditions as $condition) {
      $builtConditions[] = $condition;
    }
    return $builtConditions;
  }

}
