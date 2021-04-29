<?php

namespace Drupal\d8_service_decorator\Service;

/**
 * Class MessageServiceDecorator
 *
 * @package Drupal\d8_service_decorator\Service
 */
class MessageServiceDecorator implements MessageServiceInterface {

  /**
   * @var \Drupal\d8_service_decorator\Service\MessageService
   */
  protected $messengerService;

  /**
   * MessageServiceDecorator constructor.
   *
   * @param \Drupal\d8_service_decorator\Service\MessageService $messengerService
   */
  public function __construct(MessageService $messengerService) {
    $this->messengerService = $messengerService;
  }

  /**
   * @return string
   */
  public function getMessage() {
    return $this->messengerService->getMessage() . ' Decorator !';
  }

  /**
   * @param $message
   */
  public function display($message) {
    $this->messengerService->display($message);
  }
}
