<?php

namespace Drupal\pagerer\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Add a for Pagerer preset.
 */
class PagererPresetAddForm extends PagererPresetFormBase {

  /**
   * {@inheritdoc}
   */
  public function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = $this->t('Create');
    $actions['cancel'] = array(
      '#type' => 'link',
      '#title' => $this->t("Cancel"),
      '#url' => Url::fromRoute('entity.pagerer_preset.collection'),
      '#attributes' => ['class' => ['button']],
    );
    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    // 'core' preset id is reserved.
    $id = $form_state->getValue('id');
    if ($id == 'core') {
      $form_state->setErrorByName('id', $this->t("The pager id %preset_id is reserved for internal use.", array('%preset_id' => $id)));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $form_state->setRedirectUrl($this->entity->urlInfo('edit-form'));
    drupal_set_message($this->t('Pager %label has been created.', array('%label' => $this->entity->label())));
  }

}
