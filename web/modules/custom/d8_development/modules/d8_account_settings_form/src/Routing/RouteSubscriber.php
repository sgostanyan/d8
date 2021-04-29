<?php

namespace Drupal\d8_account_settings_form\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RouteSubscriber
 *
 * @package Drupal\d8_account_settings_form\Routing
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
     if ($collection->get('entity.user.admin_form')) {
       $route = $collection->get('entity.user.admin_form');
       $route->setDefault('_form', 'Drupal\d8_account_settings_form\Form\AlteredAccountSettingsForm');
     }
  }

}
