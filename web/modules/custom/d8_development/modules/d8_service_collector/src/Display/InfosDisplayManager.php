<?php

namespace Drupal\d8_service_collector\Display;

use Drupal\Core\Messenger\Messenger;

/**
 * Class InfosDisplayManager
 *
 * @package Drupal\d8_service_collector\Path
 */
class InfosDisplayManager implements InfosDisplayManagerInterface {

  /**
   * @var array
   */
  protected $managers = [];

  /**
   * @var array
   */
  protected $sortedManagers = [];

  /**
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * InfosDisplayManager constructor.
   *
   * @param \Drupal\Core\Messenger\Messenger $messenger
   */
  public function __construct(Messenger $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * @param \Drupal\d8_service_collector\Display\InfosDisplayManager $infoDisplayManager
   * @param int $priority
   *
   * @return $this
   */
  public function addManager(InfosDisplayManager $infoDisplayManager, $priority = 0) {
    $this->managers[$priority][] = $infoDisplayManager;
    // Force the managers to be re-sorted.
    $this->sortedManagers = NULL;
    return $this;
  }

  public function showInfos() {
    if ($this->sortedManagers === NULL) {
      $this->sortedManagers = $this->getSortedManagers();
    }
    foreach ($this->sortedManagers as $manager) {
      // Check for applies.
      if ($manager->applies()) {
        $manager->showInfos();
        // For example if we don't want to call other managers when first occurrence found (depending service priority), we can stop it.
        return;
      }
    }
    // Default message.
    $this->messenger->addMessage('Default message', 'notice');
  }

  /**
   * @return array
   */
  protected function getSortedManagers() {
    if (!isset($this->sortedManagers)) {
      // Sort the managers according to priority.
      krsort($this->managers);
      // Merge nested managers from $this->managers into $this->sortedManagers.
      $this->sortedManagers = [];
      foreach ($this->managers as $managers) {
        $this->sortedManagers = array_merge($this->sortedManagers, $managers);
      }
    }
    return $this->sortedManagers;
  }

  /**
   * @return bool
   */
  public function applies() {
    return TRUE;
  }
}
