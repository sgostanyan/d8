<?php

namespace Drupal\d8_service_alter\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\d8_service_alter\Service\MessageServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MainController
 *
 * @package Drupal\d8_service_alter\Controller
 */
class MainController extends ControllerBase {

  /**
   * @var \Drupal\d8_service_alter\Service\MessageService
   */
  protected $messageService;

  /**
   * MainController constructor.
   *
   * @param \Drupal\d8_service_alter\Service\MessageService $messageService
   */
  public function __construct(MessageServiceInterface $messageService) {
    $this->messageService = $messageService;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\d8_service_alter\Controller\MainController|static
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('d8_service_alter.message.service'));
  }

  /**
   * @return array
   */
  public function page() {

    $this->messageService->display();

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Service Alter'),
    ];
  }

}
