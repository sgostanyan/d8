<?php

namespace Drupal\custom_entity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the SL Agency type entity.
 *
 * @ConfigEntityType(
 *   id = "sl_agency_entity_type",
 *   label = @Translation("SL Agency type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\custom_entity\SLAgencyEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\custom_entity\Form\SLAgencyEntityTypeForm",
 *       "edit" = "Drupal\custom_entity\Form\SLAgencyEntityTypeForm",
 *       "delete" = "Drupal\custom_entity\Form\SLAgencyEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\custom_entity\SLAgencyEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "sl_agency_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "sl_agency_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/sl_agency_entity_type/{sl_agency_entity_type}",
 *     "add-form" = "/admin/structure/sl_agency_entity_type/add",
 *     "edit-form" = "/admin/structure/sl_agency_entity_type/{sl_agency_entity_type}/edit",
 *     "delete-form" = "/admin/structure/sl_agency_entity_type/{sl_agency_entity_type}/delete",
 *     "collection" = "/admin/structure/sl_agency_entity_type"
 *   }
 * )
 */
class SLAgencyEntityType extends ConfigEntityBundleBase implements SLAgencyEntityTypeInterface {

  /**
   * The SL Agency type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The SL Agency type label.
   *
   * @var string
   */
  protected $label;

}
