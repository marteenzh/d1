<?php

namespace Drupal\pagerer\Plugin\views\pager;

use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\views\Plugin\views\pager\Full;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The views plugin to handle Pagerer pager.
 *
 * Based on the 'Full' pager, just adds the option to select the Pagerer
 * preset to use for rendering the pager, and removes the options to define
 * the text tags.
 *
 * @ingroup views_pager_plugins
 *
 * @ViewsPager(
 *   id = "pagerer",
 *   title = @Translation("Paged output, Pagerer"),
 *   short_title = @Translation("Pagerer"),
 *   help = @Translation("Paged output, using Pagerer presets"),
 *   theme = "pagerer",
 *   register_theme = FALSE
 * )
 */
class Pagerer extends Full implements ContainerFactoryPluginInterface {

  /**
   * The entity manager service.
   *
   * @var \Drupal\Core\Entity\EntityManager
   */
  protected $entityManager;

  /**
   * The list of pagerer presets.
   *
   * @var \Drupal\Core\Entity\EntityListBuilderInterface
   */
  protected $presetsList;

  /**
   * The Pagerer preset entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $presetStorage;

  /**
   * Constructs a Drupal\Component\Plugin\PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityManager $entity_manager
   *   The entity manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManager $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
    $this->presetsList = $this->entityManager->getListBuilder('pagerer_preset');
    $this->presetStorage = $this->entityManager->getStorage('pagerer_preset');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function summaryTitle() {
    if ($preset = $this->presetStorage->load($this->options['preset'])) {
      $preset_label = $preset->label();
    }
    else {
      $preset_label = $this->t('n/a');
    }
    if (!empty($this->options['offset'])) {
      return $this->formatPlural(
        $this->options['items_per_page'],
        "Using preset %preset, @count item, skip @skip",
        "Using preset %preset, @count items, skip @skip",
        array(
          '%preset' => $preset_label,
          '@count' => $this->options['items_per_page'],
          '@skip' => $this->options['offset'],
        )
      );
    }
    return $this->formatPlural(
      $this->options['items_per_page'],
      "Using preset %preset, @count item",
      "Using preset %preset, @count items",
      array(
        '%preset' => $preset_label,
        '@count' => $this->options['items_per_page'],
      )
    );
  }

  /**
   * {@inheritdoc}
   *
   * Same as 'Full', plus preset. Tags are left even if the options form
   * will not present them, as tags in Pagerer are different than in core.
   */
  public function defineOptions() {
    $options = parent::defineOptions();
    $options['preset'] = array('default' => 'core');
    return $options;
  }

  /**
   * {@inheritdoc}
   *
   * Same as 'Full', plus preset, less tags and quantity.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $form['preset'] = array(
      '#type' => 'select',
      '#title' => $this->t('Preset'),
      '#description' => $this->t("Select the Pagerer preset to use to render the pager."),
      '#options' => $this->presetsList->listOptions(),
      '#default_value' => $this->options['preset'],
    );
    parent::buildOptionsForm($form, $form_state);
    unset(
      $form['tags'],
      $form['quantity']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function render($input) {
    return array(
      '#theme' => $this->themeFunctions(),
      '#route_name' => !empty($this->view->live_preview) ? '<current>' : '<none>',
      '#element' => $this->options['id'],
      '#parameters' => $input,
      '#config' => array(
        'preset' => $this->options['preset'],
      ),
    );
  }

}
