<?php

namespace Drupal\d8_service_collector\Display;

use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Class CustomInfosDisplayManager
 *
 * @package Drupal\d8_service_collector\Display
 */
class CustomInfosDisplayManager extends InfosDisplayManager {

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $accountProxy;

  /**
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * CustomInfosDisplayManager constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $accountProxy
   * @param \Drupal\Core\Messenger\Messenger $messenger
   */
  public function __construct(AccountProxyInterface $accountProxy, Messenger $messenger) {
    $this->accountProxy = $accountProxy;
    $this->messenger = $messenger;
  }

  /**
   * @return bool
   */
  public function applies() {
    return $this->accountProxy->isAuthenticated();
  }

  public function showInfos() {
    $name = $this->accountProxy->getAccountName();
    $this->messenger->addMessage($name, 'notice');
  }

}
