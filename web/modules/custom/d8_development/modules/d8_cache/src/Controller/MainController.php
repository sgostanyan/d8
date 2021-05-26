<?php

namespace Drupal\d8_cache\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MainController
 *
 * @package Drupal\d8_cache\Controller
 */
class MainController extends ControllerBase {

  /**
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * @var \Drupal\Core\Cache\Cache
   */
  protected $cache;

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * MainController constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   * @param \Drupal\Core\Cache\CacheFactoryInterface $cacheFactory
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(MessengerInterface $messenger, LanguageManagerInterface $languageManager, CacheFactoryInterface $cacheFactory, EntityTypeManagerInterface $entityTypeManager) {
    $this->languageManager = $languageManager;
    $this->messenger = $messenger;
    $this->cache = $cacheFactory->get('default');
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\d8_cache\Controller\MainController|static
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('messenger'), $container->get('language_manager'), $container->get('cache_factory'), $container->get('entity_type.manager'));
  }

  /**
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function page() {

    ## Cache statique ##
    $lang = $this->languageManager->getCurrentLanguage()->getId();
    $cid = 'd8_cache:' . $lang;
    $cache = $this->cache->get($cid);
    $message = '[d8_cache] ';
    if ($cache && $cache->data) {
      $message .= 'Current language from cache : ' . $cache->data;
    }
    else {
      $message .= 'Current language no cache : ' . $this->longFunction($lang);
      $this->cache->set($cid, $lang, Cache::PERMANENT, ['d8_cache_custom']);
    }
    $this->messenger->addStatus($message, 'notice');

    ## Cache de rendu ##
    $nids = $this->entityTypeManager->getStorage('node')->getQuery()->condition('type', 'article')->range(0, 1)->execute();
    $entity = $this->entityTypeManager->getStorage('node')->load(reset($nids));
    $build = $this->entityTypeManager->getViewBuilder('node')->view($entity);

    $linkBuild = [
      '#theme' => 'links',
      '#links' => [
        [
          'title' => $entity->label() . '- Link my node',
          'url' => $entity->toUrl(),
        ],
      ],
      '#cache' => [
        'keys' => ['d8_cache_link_my_nodes'],
        'contexts' => ['languages', 'user.permissions', 'user.roles'],
        'tags' => [],
        'max-age' => Cache::PERMANENT,
      ],
    ];

    $linkBuild['#cache']['contexts'] = Cache::mergeContexts($linkBuild['#cache']['contexts'], $entity->getCacheContexts());
    $linkBuild['#cache']['tags'] = Cache::mergeTags($linkBuild['#cache']['tags'], $entity->getCacheTags());
    $linkBuild['#cache']['max-age'] = Cache::mergeMaxAges($linkBuild['#cache']['max-age'], $entity->getCacheMaxAge());

    $build['link_my_node'] = $linkBuild;

    return $build;
  }

  /**
   * @param $lang
   *
   * @return mixed
   */
  protected function longFunction($lang) {
    sleep('5');
    return $lang;
  }

}
