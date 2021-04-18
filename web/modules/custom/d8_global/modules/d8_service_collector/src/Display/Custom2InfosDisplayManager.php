<?php

namespace Drupal\d8_service_collector\Display;

use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Messenger\Messenger;

/**
 * Class Custom2InfosDisplayManager
 *
 * @package Drupal\d8_service_collector\Display
 */
class Custom2InfosDisplayManager extends InfosDisplayManager {

  /**
   * @var \Drupal\Core\Language\LanguageManager
   */
  protected $languageManager;

  /**
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * Custom2InfosDisplayManager constructor.
   *
   * @param \Drupal\Core\Language\LanguageManager $languageManager
   * @param \Drupal\Core\Messenger\Messenger $messenger
   */
  public function __construct(LanguageManager $languageManager, Messenger $messenger) {
    $this->languageManager = $languageManager;
    $this->messenger = $messenger;
  }

  /**
   * @return bool
   */
  public function applies() {
    return $this->languageManager->getCurrentLanguage()->getId() != 'und';
  }

  public function showInfos() {
    $langId = $this->languageManager->getCurrentLanguage()->getId();
    $this->messenger->addMessage($langId, 'notice');
  }

}
