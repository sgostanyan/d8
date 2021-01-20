<?php

namespace Drupal\d8_global\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Controller\TitleResolverInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ArticleBreadcrumbBuilder
 *
 * @package Drupal\d8_global\Breadcrumb
 */
class ArticleBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $request;

  /**
   * @var \Drupal\Core\Controller\TitleResolverInterface
   */
  protected $titleResolver;

  /**
   * @var \Drupal\Core\Breadcrumb\Breadcrumb
   */
  protected $breadcrumb;

  /**
   * ArticleBreadcrumbBuilder constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request
   * @param \Drupal\Core\Controller\TitleResolverInterface $titleResolver
   */
  public function __construct(RequestStack $request, TitleResolverInterface $titleResolver) {
    $this->request = $request;
    $this->titleResolver = $titleResolver;
    $this->breadcrumb = new Breadcrumb();
  }

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
    return FALSE;
  }

  /**
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *
   * @return \Drupal\Core\Breadcrumb\Breadcrumb
   */
  public function build(RouteMatchInterface $route_match) {

    $this->breadcrumb->addCacheContexts(["url"]);

    $this->breadcrumb->addLink(Link::createFromRoute(t('Home'), '<front>'));

    $category = $this->request->getCurrentRequest()->get('category');

    if ($category) {
      $this->breadcrumb->addLink(Link::createFromRoute(t($category), '<none>'));
    }

    //$this->breadcrumb->addLink(Link::createFromRoute(t('Articles'), '<none>'));

    $page_title = $this->titleResolver->getTitle($this->request->getCurrentRequest(), $route_match->getRouteObject());

    if (!empty($page_title)) {
      $this->breadcrumb->addLink(Link::createFromRoute($page_title, '<none>'));
    }

    return $this->breadcrumb;
  }
}
