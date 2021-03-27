<?php

/*
@note: if you want this service alteration to be recognized automatically,
the name of this class is required to be a CamelCase version of your module's machine name followed by ServiceProvider,
it is required to be in your module's top-level namespace Drupal\your_module_name, and it must implement
*/

namespace Drupal\d8_service_alter;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

// @note: You only need Reference, if you want to change service arguments.

/**
 * Modifies the custom message service.
 */
class D8ServiceAlterServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Overrides d8_service_alter.message.service class.

    // Note: it's safest to use hasDefinition() first, because getDefinition() will
    // throw an exception if the given service doesn't exist.
    if ($container->hasDefinition('d8_service_alter.message.service')) {
      $definition = $container->getDefinition('d8_service_alter.message.service');
      $definition->setClass('Drupal\d8_service_alter\Service\MessageServiceAlter')
        // Adds entity_type.manager service as an additional argument.
        ->addArgument(new Reference('entity_type.manager'));
    }
  }

}
