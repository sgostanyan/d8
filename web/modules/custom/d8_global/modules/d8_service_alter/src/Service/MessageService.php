<?php

namespace Drupal\d8_service_alter\Service;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Class MessageService
 *
 * @package Drupal\d8_service_alter
 */
class MessageService {

  /**
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * MessageService constructor.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   */
  public function __construct(LanguageManagerInterface $languageManager, MessengerInterface $messenger, CurrentRouteMatch $currentRouteMatch) {
    $this->languageManager = $languageManager;
    $this->messenger = $messenger;
    $this->currentRouteMatch = $currentRouteMatch;
  }

  /**
   *
   */
  public function display() {
    $currentRouteName = $this->currentRouteMatch->getRouteName();
    $currentLanguageName = $this->languageManager->getCurrentLanguage()->getName();
    $this->messenger->addMessage($currentRouteName . ' ' . $currentLanguageName);
  }

}
