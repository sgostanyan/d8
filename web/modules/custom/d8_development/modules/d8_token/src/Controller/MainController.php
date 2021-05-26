<?php

namespace Drupal\d8_token\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\token\Token;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MainController
 *
 * @package Drupal\d8_service_token\Controller
 */
class MainController extends ControllerBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\token\Token
   */
  protected $token;

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * MainController constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\token\Token $token
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, Token $token, MessengerInterface $messenger, AccountInterface $currentUser) {
    $this->entityTypeManager = $entityTypeManager;
    $this->token = $token;
    $this->messenger = $messenger;
    $this->currentUser = $currentUser;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\d8_token\Controller\MainController|static
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity_type.manager'), $container->get('token'), $container->get('messenger'), $container->get('current_user'));
  }

  /**
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function page() {

    $user = $this->currentUser;
    if ($user->isAuthenticated()) {
      $id = $user->id();
      $userEntity = $id ? $this->entityTypeManager->getStorage('user')->load($id) : NULL;
      $token = $this->token;
      $message = $token->replace('[d8_token] Blend Username: [user:name-blend] - Blend Email: [user:mail-blend]', ['user' => $userEntity]);
      $this->messenger->addStatus($message);
    }

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Token'),
    ];
  }

}
