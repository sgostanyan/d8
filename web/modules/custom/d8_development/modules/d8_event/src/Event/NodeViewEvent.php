<?php

namespace Drupal\d8_event\Event;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class D8NodeViewEvent
 *
 * @package Drupal\d8_event\Event
 */
class NodeViewEvent extends Event {

  const EVENT_NAME = 'd8_event.node_view_event';

  /**
   * @var string
   */
  public $bundle;

  /**
   * @var \Drupal\Core\Entity\Display\EntityViewDisplayInterface
   */
  public $display;

  /**
   * @var string
   */
  public $viewMode;

  /**
   * D8NodeViewEvent constructor.
   *
   * @param \Drupal\node\NodeInterface $node
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   * @param string $viewMode
   */
  public function __construct(NodeInterface $node, EntityViewDisplayInterface $display, string $viewMode) {
    $this->bundle = $node->bundle();
    $this->display = $display;
    $this->viewMode = $viewMode;
  }

}
