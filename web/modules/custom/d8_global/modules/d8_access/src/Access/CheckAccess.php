<?php

namespace Drupal\d8_access\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CheckAccess
 *
 * @package Drupal\d8_access\Access
 */
class CheckAccess implements ContainerInjectionInterface {

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $user;

  /**
   * CheckAccess constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $user
   */
  public function __construct(AccountProxyInterface $user) {
    $this->user = $user;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('current_user'));
  }

  /**
   * @return \Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultForbidden
   */
  public function check() {
    if ($this->user->isAuthenticated()) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }
}
