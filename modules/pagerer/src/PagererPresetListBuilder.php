<?php

namespace Drupal\pagerer;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityHandlerInterface;

/**
 * Provides a listing of Pagerer presets.
 */
class PagererPresetListBuilder extends ConfigEntityListBuilder implements EntityHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Pager name');
    $header['preview'] = $this->t('Preview');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['name'] = $this->getLabel($entity);
    $row['preview']['class'] = array('pagerer-admin-preset-preview');
    $row['preview']['data'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer',
      '#element' => 5,
      '#config' => array(
        'preset' => $entity->id(),
      ),
    );
    return $row + parent::buildRow($entity);
  }

  /**
   * Create a list of presets suitable for selection.
   */
  public function listOptions() {
    $entities = $this->load();
    $list = array();
    if ($entities) {
      foreach ($entities as $preset) {
        $list[$preset->id()] = $preset->label();
      }
    }
    return $list;
  }

}
