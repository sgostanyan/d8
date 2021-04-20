<?php

namespace Drupal\d8_mail\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class MainController
 *
 * @package Drupal\d8_mail\Controller
 */
class MainController extends ControllerBase {

  /**
   * @return array
   */
  public function mail() {
    $currentUser = Drupal::currentUser();
    if ($currentUser->isAuthenticated()) {
      $to = $currentUser->getEmail();
    }
    else {
      $to = 'sgostanyan@gmail.com';
    }
    $replyTo = Drupal::config('system.site')->get('mail');
    $langcode = 'fr';
    $params = [
      'subject' => 'TEST EMAIL',
      'body' => 'YOLOLO',
    ];

    Drupal::service('plugin.manager.mail')->mail('d8_global', 'test_email', $to, $langcode, $params, $replyTo, TRUE);

    return [
      '#theme' => 'mail_template',
      '#var' => $this->t('Test Mail'),
      '#attached' => [
        'library' => ['d8_global/global-styling'],
      ],
    ];

  }
}
