<?php

namespace Drupal\d8_event\Permission;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class PrivatePagePermissions
 *
 * @package Drupal\d8_event\Permission
 */
class PrivatePagePermissions {

  use StringTranslationTrait;

  const PERMISSION_NAME = 'access private_page';

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
        'description' => $this->t('Access to the page of %label type',
          [
            '%label' => 'Private page',
          ]),
      ],
    ];
  }

}
