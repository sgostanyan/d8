<?php

namespace Drupal\d8_global\Breadcrumb;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;

/**
 * Class ArticleBreadcrumbBuilder
 *
 * @package Drupal\d8_global\Breadcrumb
 */
class ArticleBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *
   * @return bool
   */
  public function applies(RouteMatchInterface $route_match) {
    $parameters = $route_match->getParameters()->all();
    if (isset($parameters['node'])) {
      return $parameters['node']->getType() === 'article';
    }
  }

  /**
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *
   * @return \Drupal\Core\Breadcrumb\Breadcrumb
   */
  public function build(RouteMatchInterface $route_match) {

    $breadcrumb = new Breadcrumb();

    $breadcrumb->addCacheContexts(["url"]);

    $breadcrumb->addLink(Link::createFromRoute(t('Home'), '<front>'));
    $breadcrumb->addLink(Link::createFromRoute(t('Articles'), '<none>'));

    $request = \Drupal::request();
    $route_match = \Drupal::routeMatch();
    $page_title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());

    if (!empty($page_title)) {
      $breadcrumb->addLink(Link::createFromRoute($page_title, '<none>'));
    }

    return $breadcrumb;
  }
}
