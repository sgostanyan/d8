<?php

namespace Drupal\budge\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'BudgeOverviewBlock' block.
 *
 * @Block(
 *  id = "budge_overview_block",
 *  admin_label = @Translation("Budge overview block"),
 * )
 */
class BudgeOverviewBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\budge\Manager\BudgeManager definition.
   *
   * @var \Drupal\budge\Manager\BudgeManager
   */
  protected $budgeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->budgeManager = $container->get('budge.main.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['show_monthly_expenses_amount'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show monthly expenses amount'),
      '#default_value' => $this->configuration['show_monthly_expenses_amount'],
      '#weight' => '0',
    ];
    $form['show_ponctual_expenses_amount'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show ponctual expenses amount'),
      '#default_value' => $this->configuration['show_ponctual_expenses_amount'],
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['show_monthly_expenses_amount'] = $form_state->getValue('show_monthly_expenses_amount');
    $this->configuration['show_ponctual_expenses_amount'] = $form_state->getValue('show_ponctual_expenses_amount');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'budge_block',
      '#content' => [
        'budgets' => $this->budgeManager->getBudgets(),
        'showMonthlyExpenses' => $this->configuration['show_monthly_expenses_amount'],
        'showPonctualExpenses' => $this->configuration['show_ponctual_expenses_amount'],
      ],
    ];
  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
