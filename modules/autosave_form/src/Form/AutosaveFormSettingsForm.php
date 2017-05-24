<?php

namespace Drupal\autosave_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure autosave form settings for this site.
 */
class AutosaveFormSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'autosave_form_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'autosave_form.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('autosave_form.settings');

    $form['interval'] = [
      '#type' => 'number',
      '#title' => $this->t('The interval to use for triggering autosave in milliseconds.'),
      '#default_value' => $config->get('interval'),
    ];

    $form['active_on'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Active on:'),
      '#tree' => TRUE,
    ];
    $form['active_on']['content_entity_forms'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Content Entity Forms'),
      '#default_value' => $config->get('active_on')['content_entity_forms'],
    ];
    $form['active_on']['config_entity_forms'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Config Entity Forms'),
      '#default_value' => $config->get('active_on')['config_entity_forms'],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\Core\Config\Config $config */
    $config = $this->config('autosave_form.settings');
    $config->set('interval', $form_state->getValue('interval'))
      ->set('active_on', $form_state->getValue('active_on'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
