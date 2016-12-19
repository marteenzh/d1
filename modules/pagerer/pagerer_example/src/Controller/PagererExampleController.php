<?php

namespace Drupal\pagerer_example\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller class for Pagerer example.
 */
class PagererExampleController extends ControllerBase {

  /**
   * Get pagerer example page title.
   *
   * @return string
   *   The page title.
   */
  public function examplePageTitle() {
    // Set the page title to show current Pagerer version.
    $module_info = system_get_info('module', 'pagerer');
    return $this->t("Pagerer @version - example page", array('@version' => $module_info['version']));
  }

  /**
   * Build the pagerer example page.
   *
   * @return array
   *   A render array.
   */
  public function examplePage() {

    // First data table - associated to pager element 0.
    $header_0 = array(
      array('data' => 'wid'),
      array('data' => 'type'),
      array('data' => 'timestamp'),
    );
    $query_0 = db_select('watchdog', 'd')->extend('Drupal\Core\Database\Query\PagerSelectExtender')->element(0);
    $result_0 = $query_0
      ->fields('d', array('wid', 'type', 'timestamp'))
      ->limit(5)
      ->orderBy('d.wid')
      ->execute();
    $rows_0 = array();
    foreach ($result_0 as $row) {
      $rows_0[] = array('data' => (array) $row);
    }

    // Second data table - associated to pager element 1.
    $header_1 = array(
      array('data' => 'collection'),
      array('data' => 'name'),
    );
    $query_1 = db_select('key_value', 'd')->extend('Drupal\Core\Database\Query\PagerSelectExtender')->element(1);
    $result_1 = $query_1
      ->fields('d', array('collection', 'name'))
      ->limit(10)
      ->orderBy('d.collection')
      ->orderBy('d.name')
      ->execute();
    $rows_1 = array();
    foreach ($result_1 as $row) {
      $rows_1[] = array('data' => (array) $row);
    }

    // Third data table - associated to pager element 2.
    $header_2 = array(
      array('data' => 'name'),
      array('data' => 'path'),
    );
    $query_2 = db_select('router', 'd')->extend('Drupal\Core\Database\Query\PagerSelectExtender')->element(2);
    $result_2 = $query_2
      ->fields('d', array('name', 'path'))
      ->limit(5)
      ->orderBy('d.name')
      ->execute();
    $rows_2 = array();
    foreach ($result_2 as $row) {
      $rows_2[] = array('data' => (array) $row);
    }

    // Create a render array ($build) which will be themed for output.
    $build = array();

    // Some description.
    $build['initdesc'] = array('#markup' => $this->t("This page is an example of pagerer's features. It runs three separate queries on the database, and renders three tables with the results. A distinct pager is associated to each of the tables, and each pager is rendered through various pagerer's styles.") . '<p/><hr/>');

    // First table.
    $build['l_pager_table_0'] = array('#markup' => '<br/><br/><h2><b>' . $this->t("First data table:") . '</b></h2>');
    $build['pager_table_0'] = array(
      '#theme' => 'table',
      '#header' => $header_0,
      '#rows' => $rows_0,
      '#empty' => $this->t("There are no watchdog records found in the db"),
    );

    // Attach the pager themes.
    $build['l_pager_pager_0'] = array('#markup' => '<b>' . $this->t("Drupal standard 'pager' theme:") . '</b>');
    $build['pager_pager_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pager',
      '#element' => 0,
    );
    $build['l_pagerer_standard_0'] = array('#markup' => '<br/>' . $this->t("<b>'Standard' pagerer style (mimick of Drupal's standard)</b> in three 'display' modes: 'pages', 'items', and 'item_ranges'"));
    $build['pagerer_standard_pages_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 0,
      '#style' => 'standard',
      '#config' => array(
        'display_restriction' => 0,
      ),
    );
    $build['pagerer_standard_items_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 0,
      '#style' => 'standard',
      '#config' => array(
        'display_restriction' => 0,
        'display' => 'items',
      ),
    );
    $build['pagerer_standard_item_ranges_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 0,
      '#style' => 'standard',
      '#config' => array(
        'display_restriction' => 0,
        'display' => 'item_ranges',
      ),
    );
    $build['l_pagerer_progressive_0'] = array('#markup' => '<br/><b>' . $this->t("'Progressive' pagerer style:") . '</b>');
    $build['pagerer_progressive_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 0,
      '#style' => 'progressive',
      '#config' => array(
        'display_restriction' => 0,
      ),
    );
    $build['l_pagerer_adaptive_0'] = array('#markup' => '<br/><b>' . $this->t("'Adaptive' pagerer style:") . '</b>');
    $build['pagerer_adaptive_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 0,
      '#style' => 'adaptive',
      '#config' => array(
        'display_restriction' => 0,
      ),
    );
    $build['l_pagerer_mini_0'] = array('#markup' => '<br/><b>' . $this->t("'Mini' pagerer style:") . '</b>');
    $build['pagerer_mini_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 0,
      '#style' => 'mini',
      '#config' => array(
        'display_restriction' => 0,
      ),
    );
    $build['l_pagerer_scrollpane_0'] = array('#markup' => '<br/><b>' . $this->t("'Scrollpane' pagerer style:") . '</b>');
    $build['pagerer_scrollpane_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 0,
      '#style' => 'scrollpane',
      '#config' => array(
        'display_restriction' => 0,
      ),
    );
    $build['l_pagerer_slider_0'] = array('#markup' => '<br/><b>' . $this->t("'Slider' pagerer style:") . '</b>');
    $build['pagerer_slider_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 0,
      '#style' => 'slider',
      '#config' => array(
        'display_restriction' => 0,
      ),
    );

    $build['l_pagerer_pagerer_0'] = array('#markup' => '<br/><b>' . $this->t("'pagerer' core replacement theme:") . '</b>');
    $build['pagerer_pagerer_0'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer',
      '#element' => 0,
      '#config' => array(
        'preset' => $this->config('pagerer.settings')->get('core_override_preset'),
      ),
    );

    $build['end_table_0'] = array(
      '#markup' => '<p/><hr/>',
    );

    // Second table.
    $build['l_pager_table_1'] = array('#markup' => '<br/><br/><h2><b>' . $this->t("Second data table:") . '</b></h2>');
    $build['pager_table_1'] = array(
      '#theme' => 'table',
      '#header' => $header_1,
      '#rows' => $rows_1,
      '#empty' => $this->t("There are no date formats found in the db"),
    );

    // Attach the pager themes.
    $build['l_pagerer_basic_1'] = array('#markup' => '<br/><b>' . $this->t("'Basic' pagerer style:") . '</b>');
    $build['pagerer_basic_1'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 1,
      '#style' => 'basic',
      '#config' => array(
        'display_restriction' => 0,
      ),
    );
    $build['l_pagerer_adaptive_1'] = array('#markup' => '<br/><b>' . $this->t("'Adaptive' pagerer style:") . '</b>');
    $build['pagerer_adaptive_1'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 1,
      '#style' => 'adaptive',
      '#config' => array(
        'display_restriction' => 0,
      ),
    );
    $build['l_pagerer_pagerer_1'] = array('#markup' => '<br/><b>' . $this->t("'pagerer' core replacement theme:") . '</b>');
    $build['pagerer_pagerer_1'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer',
      '#element' => 1,
      '#config' => array(
        'preset' => $this->config('pagerer.settings')->get('core_override_preset'),
      ),
    );
    $build['l_pagerer_pagerer_direct_1'] = array('#markup' => '<br/><b>' . $this->t("'pagerer' (direct call from module) theme:") . '</b> ' . $this->t("Note the usage of the 'tags' variables to customise labels and hover titles."));
    $build['pagerer_pagerer_direct_1'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer',
      '#element' => 1,
      '#config' => array(
        'panes' => [
          'left' => array(
            'style' => 'mini',
            'config' => array(
              'tags'            => array(
                'items'         => array(
                  'first_title'    => $this->t("Go to the beginning of the recordset"),
                  'previous_title' => $this->t("Go to the previous range of records"),
                ),
              ),
              'display'         => 'items',
              'display_mode'    => 'none',
              'prefix_display'  => FALSE,
              'suffix_display'  => FALSE,
              'first_link'      => 'always',
              'previous_link'   => 'always',
              'next_link'       => 'never',
              'last_link'       => 'never',
            ),
          ),
          'center' => array(
            'style' => 'mini',
            'config' => array(
              'tags'            => array(
                'items'         => array(
                  'prefix_label'    => $this->t("Record"),
                  'widget_title'    => $this->t("Enter record and press Return. Up/Down arrow keys are enabled."),
                ),
              ),
              'display_restriction' => 0,
              'display'         => 'items',
              'display_mode'    => 'widget',
              'prefix_display'  => TRUE,
              'suffix_display'  => TRUE,
              'first_link'      => 'never',
              'previous_link'   => 'never',
              'next_link'       => 'never',
              'last_link'       => 'never',
            ),
          ),
          'right' => array(
            'style' => 'mini',
            'config' => array(
              'tags'            => array(
                'items'         => array(
                  'next_title'     => $this->t("Go to the next range of records"),
                  'last_title'     => $this->t("Go to the end of the recordset"),
                ),
              ),
              'display'         => 'items',
              'display_mode'    => 'none',
              'prefix_display'  => FALSE,
              'suffix_display'  => FALSE,
              'first_link'      => 'never',
              'previous_link'   => 'never',
              'next_link'       => 'always',
              'last_link'       => 'always',
            ),
          ),
        ],
      ),
    );

    $build['end_table_1'] = array(
      '#markup' => '<p/><hr/>',
    );

    // Third table.
    $build['l_pager_table_2'] = array('#markup' => '<br/><br/><h2><b>' . $this->t("Third data table:") . '</b></h2>');
    $build['pager_table_2'] = array(
      '#theme' => 'table',
      '#header' => $header_2,
      '#rows' => $rows_2,
      '#empty' => $this->t("There are no routes found in the db"),
    );

    // Attach the pager themes.
    $build['l_pagerer_adaptive_2'] = array('#markup' => '<br/><b>' . $this->t("'Adaptive' pagerer style:") . '</b>');
    $build['pagerer_adaptive_2'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer_base',
      '#element' => 2,
      '#style' => 'adaptive',
      '#config' => array(
        'display_restriction' => 0,
      ),
    );
    $build['l_pagerer_pagerer_2'] = array('#markup' => '<br/><b>' . $this->t("'pagerer' core replacement theme:") . '</b>');
    $build['pagerer_pagerer_2'] = array(
      '#type' => 'pager',
      '#theme' => 'pagerer',
      '#element' => 2,
      '#config' => array(
        'preset' => $this->config('pagerer.settings')->get('core_override_preset'),
      ),
    );

    $build['end_table_2'] = array(
      '#markup' => '<p/><hr/>',
    );

    return $build;
  }

}
