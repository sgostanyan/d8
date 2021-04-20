<?php

namespace Drupal\d8_event\EventSubscriber;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\d8_event\Event\NodeViewEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class NodeViewEventSubscriber
 *
 * @package Drupal\d8_event\EventSubscriber
 */
class NodeViewEventSubscriber implements EventSubscriberInterface, ContainerInjectionInterface {

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $user;

  /**
   * EntityUpdateEventSubscriber constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $user
   */
  public function __construct(AccountProxyInterface $user) {
    $this->user = $user;
  }

  /**
   * Returns an array of event names this subscriber wants to listen to.
   *
   * The array keys are event names and the value can be:
   *
   *  * The method name to call (priority defaults to 0)
   *  * An array composed of the method name to call and the priority
   *  * An array of arrays composed of the method names to call and respective
   *    priorities, or 0 if unset
   *
   * For instance:
   *
   *  * ['eventName' => 'methodName']
   *  * ['eventName' => ['methodName', $priority]]
   *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
   *
   * The code must not depend on runtime state as it will only be called at compile time.
   * All logic depending on runtime state must be put into the individual methods handling the events.
   *
   * @return array The event names to listen to
   */
  public static function getSubscribedEvents(): array {
    return [NodeViewEvent::EVENT_NAME => 'onCheckPermission'];
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
   * @param \Drupal\d8_event\Event\NodeViewEvent $event
   */
  public function onCheckPermission(NodeViewEvent $event) {
    if ($event->bundle == 'private_page') {
      if (!$this->user->isAuthenticated()) {
        throw new AccessDeniedHttpException();
      }
    }
  }
}

