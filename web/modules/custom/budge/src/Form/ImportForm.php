<?php

namespace Drupal\budge\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ImportForm.
 */
class ImportForm extends FormBase {

  /**
   * Drupal\budge\Manager\BudgeExportManager definition.
   *
   * @var \Drupal\budge\Manager\BudgeExportManager
   */
  protected $budgeExportManager;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Drupal\Core\File\FileSystem definition.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * Drupal\Core\Messenger\Messenger definition.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->budgeExportManager = $container->get('budge.export.manager');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->fileSystem = $container->get('file_system');
    $instance->messenger = $container->get('messenger');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'import_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['yaml_export'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('YAML export'),
      '#weight' => '0',
      '#description' => t('Allowed extensions: yml'),
      '#upload_location' => 'private://budge',
      '#upload_validators' => [
        'file_validate_extensions' => ['yml'],
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fid = $form_state->getValue('yaml_export');
    $fid = !empty($fid) ? reset($fid) : NULL;
    $fileEntity = $fid ? File::load($fid) : NULL;
    $fileUri = $fileEntity ? $fileEntity->getFileUri() : NULL;
    if ($fileUri) {
      if ($this->fileSystem->copy($fileUri,
        $this->budgeExportManager->getFilePath(),
        TRUE)) {
        $this->budgeExportManager->importBudget();
      }
      $this->messenger->addMessage(t('Successfull imported'),
        Messenger::TYPE_STATUS);
    }
    $this->messenger->addMessage(t('An error occurred during processing'),
      Messenger::TYPE_ERROR);
  }
}
