<?php

namespace Drupal\d8_plugin_block\Plugin\Block;

use Drupal\Component\Datetime\Time;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'MessageBlock' block.
 *
 * @Block(
 *  id = "message_block",
 *  admin_label = @Translation("Message block"),
 * )
 */
class MessageBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Component\Datetime\Time
   */
  protected Time $time;

  /**
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected DateFormatter $dateFormatter;

  /**
   * @var int|mixed
   */
  protected $now;

  /**
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * MessageBlock constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Component\Datetime\Time $time
   * @param \Drupal\Core\Datetime\DateFormatter $dateFormatter
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cacheTagsInvalidator
   *
   * @throws \Exception
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Time $time, DateFormatter $dateFormatter, CacheTagsInvalidatorInterface $cacheTagsInvalidator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->time = $time;
    $this->dateFormatter = $dateFormatter;
    $this->now = $this->time->getRequestTime();
    $this->cacheTagsInvalidator = $cacheTagsInvalidator;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return \Drupal\d8_plugin_block\Plugin\Block\MessageBlock|static
   * @throws \Exception
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('datetime.time'), $container->get('date.formatter'), $container->get('cache_tags.invalidator'));
  }

  /**
   * @param $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['date_start'] = [
      '#type' => 'date',
      '#title' => $this->t('Who'),
      '#default_value' => $config['date_start'] ?? '',
    ];

    $form['date_end'] = [
      '#type' => 'date',
      '#title' => $this->t('Who'),
      '#default_value' => $config['date_end'] ?? '',
    ];

    return $form;
  }

  /**
   * @param $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['date_start'] = $values['date_start'];
    $this->configuration['date_end'] = $values['date_end'];
    $this->cacheTagsInvalidator->invalidateTags(['d8_plugin_block_message']);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build = [];
    if ($this->now < $this->start) {
      return $build;
    }

    $build['#title'] = 'TITRE';
    $build['#attributes'] = [
      'class' => ['announcement'],
    ];

    $dates = $this->getDates();

    $build['announcement'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['announcement__content']],
      'wrapper' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['wrapper_content']],
        'content' => [
          '#type' => 'processed_text',
          '#text' => '<p>' . $this->dateFormatter->format($dates['start'], '', 'd/m/Y') . ' - ' . $this->dateFormatter->format($dates['end'], '', 'd/n/Y') . '</p>',
          '#format' => "full_html",
        ],
      ],
    ];

    return $build;
  }

  /**
   * @return int
   * @throws \Exception
   */
  public function getCacheMaxAge() {
    $max_age = parent::getCacheMaxAge();
    $dates = $this->getDates();
    if ($this->now < $dates['start']) {
      $max_age = Cache::mergeMaxAges($max_age, $dates['start'] - $this->now);
    }
    elseif ($this->now < $dates['end']) {
      $max_age = Cache::mergeMaxAges($max_age, $dates['end'] - $this->now);
    }
    return $max_age;
  }

  /**
   * @return array
   * @throws \Exception
   */
  protected function getDates() {
    return [
      'start' => (new \DateTime($this->configuration['date_start'] . ' 00:00:00'))->getTimestamp(),
      'end' => (new \DateTime($this->configuration['date_end'] . ' 23:59:59'))->getTimestamp(),
    ];
  }

  /**
   * @return array|string[]
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['d8_plugin_block_message']);
  }

}
