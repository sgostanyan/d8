<?php

/**
 * @file
 * Contains sl_agency_entity.page.inc.
 *
 * Page callback for SL Agency entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for SL Agency templates.
 *
 * Default template: sl_agency_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_sl_agency_entity(array &$variables) {
  // Fetch SLAgencyEntity Entity Object.
  $sl_agency_entity = $variables['elements']['#sl_agency_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
