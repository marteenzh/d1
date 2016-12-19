<?php

namespace Drupal\pagerer\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\ElementInfoManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Delete a Pagerer preset.
 */
class PagererPresetDeleteForm extends EntityDeleteForm {

  /**
   * The element info manager.
   *
   * @var \Drupal\Core\Render\ElementInfoManagerInterface
   */
  protected $elementInfoManager;

  /**
   * Constructs a PagererPresetDeleteForm form.
   *
   * @param \Drupal\Core\Render\ElementInfoManagerInterface $element_info_manager
   *   The element info manager.
   */
  public function __construct(ElementInfoManagerInterface $element_info_manager) {
    $this->elementInfoManager = $element_info_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.element_info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->configFactory()->getEditable('pagerer.settings');
    if ($config->get('core_override_preset') == $this->getEntity()->id()) {
      $config->set('core_override_preset', 'core')->save();
      $this->elementInfoManager->clearCachedDefinitions();
      drupal_set_message($this->t("Pager %preset_label was being used as replacement of Drupal's core pager. Drupal's core pager has been reset as main pager.", array('%preset_label' => $this->getEntity()->label())), 'warning');
    }
  }

}
