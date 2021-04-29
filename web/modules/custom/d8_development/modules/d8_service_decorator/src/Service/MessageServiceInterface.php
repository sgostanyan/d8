<?php

namespace Drupal\d8_service_decorator\Service;


/**
 * Class MessageService
 *
 * @package Drupal\d8_service_decorator\Service
 */
interface MessageServiceInterface {

  /**
   * @return string
   */
  public function getMessage();

  /**
   * @param $message
   */
  public function display($message);
}
