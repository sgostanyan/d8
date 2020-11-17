<?php

namespace Drupal\list_page\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleListPageController
 *
 * @package Drupal\list_page\Controller
 */
class ArticleListPageController extends ListPageController {

  const CONFIG_ENTITY_TYPE = 'node';

  const CONFIG_BUNDLE_FIELD = 'type';

  const CONFIG_BUNDLE_ID = 'article';

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

    if (self::PAGER !== NULL && $request->get('page') === NULL) {
      $request->query->set('page', 0);
    }

    $lisPageManager = $this->listPageManager->create(self::CONFIG_ENTITY_TYPE, self::CONFIG_BUNDLE_FIELD, self::CONFIG_BUNDLE_ID);
    $result = $lisPageManager->getResults(self::CONTENT_ENTITY_TYPE, $this->buildConditions(), self::PAGER);

    $render = [];
    $render[] = [
      '#theme' => 'main_page',
      '#result' => $result,
      '#type' => 'pager',
    ];
    $render[] = ['#type' => 'pager'];

    return $render;

  }

  /**
   * @param array|null $conditions
   *  Array of array [field, value, operator]
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
    $builtConditions = [
      [
        self::CONFIG_BUNDLE_FIELD,
        self::CONTENT_BUNDLE_IDS,
        'IN',
      ],
    ];
    foreach ($conditions as $condition) {
      $builtConditions[] = $condition;
    }
    return $builtConditions;
  }

}
