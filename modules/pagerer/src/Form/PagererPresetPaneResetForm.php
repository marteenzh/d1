<?php

namespace Drupal\pagerer\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\pagerer\PagererFactory;
use Drupal\pagerer\Plugin\PagererStyleManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for Pagerer presets' panes.
 */
class PagererPresetPaneResetForm extends EntityConfirmFormBase {

  /**
   * Pagerer pane label literals.
   *
   * @var array
   */
  protected $paneLabels;

  /**
   * Pagerer pane being edited.
   *
   * @var string
   */
  protected $pane;

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
   * Constructs the form object.
   *
   * @param \Drupal\pagerer\PagererFactory $pagerer_factory
   *   The Pagerer factory.
   * @param \Drupal\pagerer\Plugin\PagererStyleManager $style_manager
   *   The plugin manager for Pagerer style plugins.
   */
  public function __construct(PagererFactory $pagerer_factory, PagererStyleManager $style_manager) {
    $this->pagererFactory = $pagerer_factory;
    $this->styleManager = $style_manager;
    $this->paneLabels = array(
      'left' => $this->t('left'),
      'center' => $this->t('center'),
      'right' => $this->t('right'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('pagerer.factory'),
      $container->get('pagerer.style.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $pane = NULL) {
    $this->pane = $pane;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t(
      "Reset @pane pane configuration?",
      array(
        '@pane' => $this->paneLabels[$this->pane],
      )
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    $style = $this->entity->getPaneData($this->pane, 'style');
    $plugin_definition = $this->styleManager->getDefinition($style);
    return $this->t(
      "The %pane pane of pager %preset_name will be reset to %style style default configuration.",
      array(
        '%preset_name' => $this->entity->label(),
        '%pane' => $this->paneLabels[$this->pane],
        '%style' => !empty($plugin_definition) ? $plugin_definition['short_title'] : NULL,
      )
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Reset');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return $this->entity->urlInfo('edit-form');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $style = $this->entity->getPaneData($this->pane, 'style');
    $plugin_definition = $this->styleManager->getDefinition($style);
    $this->entity->setPaneData($this->pane, 'config', []);
    $this->entity->save();
    drupal_set_message(
      $this->t(
        'The %pane pane configuration has been reset to %style style default configuration.',
        array(
          '%style' => !empty($plugin_definition) ? $plugin_definition['short_title'] : NULL,
          '%pane' => $this->paneLabels[$this->pane],
        )
      ),
      'status'
    );
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
