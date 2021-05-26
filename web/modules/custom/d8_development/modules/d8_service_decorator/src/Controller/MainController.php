<?php

namespace Drupal\d8_service_decorator\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\d8_service_decorator\Service\MessageServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class MainController
 *
 * @package Drupal\d8_service_decorator\Controller
 */
class MainController extends ControllerBase {

  /**
   * @var \Drupal\d8_service_alter\Service\MessageService
   */
  protected $messageService;

  /**
   * MainController constructor.
   *
   * @param \Drupal\d8_service_decorator\Service\MessageServiceInterface $messageService
   */
  public function __construct(MessageServiceInterface $messageService) {
    $this->messageService = $messageService;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\d8_service_decorator\Controller\MainController|static
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('d8_service_decorator.message.service'));
  }

  /**
   * @return array
   */
  public function page() {

    $message = $this->messageService->getMessage();
    $this->messageService->display($message);

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Service Decorator'),
    ];
  }

}
