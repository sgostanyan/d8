<?php

namespace Drupal\d8_service_alter\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Class MessageServiceAlter
 *
 * @package Drupal\d8_service_alter\Service
 */
class MessageServiceAlter {

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
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * MessageService constructor.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   */
  public function __construct(LanguageManagerInterface $languageManager, MessengerInterface $messenger, CurrentRouteMatch $currentRouteMatch, EntityTypeManagerInterface $entityTypeManager) {
    $this->languageManager = $languageManager;
    $this->messenger = $messenger;
    $this->currentRouteMatch = $currentRouteMatch;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   *
   */
  public function display() {
    $currentRouteName = $this->currentRouteMatch->getRouteName();
    $currentLanguageName = $this->languageManager->getCurrentLanguage()->getName();

    // EntityTypeManger has been added dynamically in ServiceProvider class.
    $entityTypeId = $this->entityTypeManager->getStorage('node')->getEntityTypeId();

    $this->messenger->addMessage($currentRouteName . ' ' . $currentLanguageName . ' ALTER ! ' . $entityTypeId);
  }

}
