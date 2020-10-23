<?php

namespace Drupal\d8_global\Drush;

use Drush\Commands\DrushCommands;

/**
 * A drush command file.
 *
 * @package Drupal\drush9_custom_commands\Drush
 */
class Drush9CustomCommands extends DrushCommands {

  /**
   * Drush command that displays the given text.
   *
   * @param string $text
   *   Argument with message to be displayed.
   * @command drush9_custom_commands:message
   * @aliases d9-message d9-msg
   * @option uppercase
   *   Uppercase the message.
   * @option reverse
   *   Reverse the message.
   * @usage drush9_custom_commands:message --uppercase --reverse drupal8
   */
  public function message($text = 'Hello world!', $options = ['uppercase' => FALSE, 'reverse' => FALSE]) {
    if ($options['uppercase']) {
      $text = strtoupper($text);
    }
    if ($options['reverse']) {
      $text = strrev($text);
    }
    $this->output()->writeln($text);
  }

}
