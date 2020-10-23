<?php

namespace Drupal\custom_entity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining SL Agency entities.
 *
 * @ingroup custom_entity
 */
interface SLAgencyEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the SL Agency name.
   *
   * @return string
   *   Name of the SL Agency.
   */
  public function getName();

  /**
   * Sets the SL Agency name.
   *
   * @param string $name
   *   The SL Agency name.
   *
   * @return \Drupal\custom_entity\Entity\SLAgencyEntityInterface
   *   The called SL Agency entity.
   */
  public function setName($name);

  /**
   * Gets the SL Agency creation timestamp.
   *
   * @return int
   *   Creation timestamp of the SL Agency.
   */
  public function getCreatedTime();

  /**
   * Sets the SL Agency creation timestamp.
   *
   * @param int $timestamp
   *   The SL Agency creation timestamp.
   *
   * @return \Drupal\custom_entity\Entity\SLAgencyEntityInterface
   *   The called SL Agency entity.
   */
  public function setCreatedTime($timestamp);

}
