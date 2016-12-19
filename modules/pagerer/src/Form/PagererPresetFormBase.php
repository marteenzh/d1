<?php

namespace Drupal\pagerer\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\pagerer\PagererFactory;
use Drupal\pagerer\Plugin\PagererStyleManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base form handler for image style add and edit forms.
 */
abstract class PagererPresetFormBase extends EntityForm {

  /**
   * The Pagerer preset entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $pagererPresetStorage;

  /**
   * The Pagerer factory.
   *
   * @var \Drupal\pagerer\PagererFactory
   */
  protected $pagererFactory;

  /**
   * The plugin manager for Pagerer style plugins.
   *
   * @var \Drupal\pagerer\Plugin\PagererStyleManager
   */
  protected $styleManager;

  /**
   * Constructs a base class for pagerer preset add and edit forms.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $pagerer_preset_storage
   *   The Pagerer preset entity storage.
   * @param \Drupal\pagerer\PagererFactory $pagerer_factory
   *   The Pagerer factory.
   * @param \Drupal\pagerer\Plugin\PagererStyleManager $style_manager
   *   The plugin manager for Pagerer style plugins.
   */
  public function __construct(EntityStorageInterface $pagerer_preset_storage, PagererFactory $pagerer_factory, PagererStyleManager $style_manager) {
    $this->pagererPresetStorage = $pagerer_preset_storage;
    $this->pagererFactory = $pagerer_factory;
    $this->styleManager = $style_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('pagerer_preset'),
      $container->get('pagerer.factory'),
      $container->get('pagerer.style.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Pager name'),
      '#default_value' => $this->entity->label(),
      '#required' => TRUE,
    );
    $form['id'] = array(
      '#type' => 'machine_name',
      '#machine_name' => array(
        'exists' => array($this->pagererPresetStorage, 'load'),
        'source' => array('label'),
      ),
      '#default_value' => $this->entity->id(),
      '#required' => TRUE,
    );
    return parent::form($form, $form_state);
  }

}
