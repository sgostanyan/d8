<?php

namespace Drupal\list_page;

/**
 * Interface ListPageInterface
 */
interface ListPageInterface {

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return mixed
   */
  public function page(\Symfony\Component\HttpFoundation\Request $request);
}
