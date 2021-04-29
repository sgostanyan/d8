<?php

namespace Drupal\d8_service_decorator\Service;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Class MessageService
 *
 * @package Drupal\d8_service_decorator\Service
 */
class MessageService implements MessageServiceInterface {

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
   * @return string
   */
  public function getMessage() {
    $currentRouteName = $this->currentRouteMatch->getRouteName();
    $currentLanguageName = $this->languageManager->getCurrentLanguage()->getName();
    return $currentRouteName . ' ' . $currentLanguageName;
  }

  /**
   * @param $message
   */
  public function display($message) {
    $this->messenger->addMessage($message);
  }

}
