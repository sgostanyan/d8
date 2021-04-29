<?php

namespace Drupal\d8_permission\Permission;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class PrivatePagePermissions
 *
 * @package Drupal\d8_permission\Permission
 */
class PrivatePagePermissions {

  use StringTranslationTrait;

  const PERMISSION_NAME = 'access private pages';

  /**
   * @return array[]
   */
  public function permissions(): array {
    return [
      self::PERMISSION_NAME => [
        'title' => $this->t('Access to the %label node',
          [
            '%label' => 'Private page',
          ]),
        'description' => $this->t('Access to %label',
          [
            '%label' => 'Private page',
          ]),
      ],
    ];
  }

}
