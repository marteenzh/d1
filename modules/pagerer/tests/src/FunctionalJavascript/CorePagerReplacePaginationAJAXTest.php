<?php

namespace Drupal\Tests\pagerer\FunctionalJavascript;

use Drupal\Tests\views\FunctionalJavascript\PaginationAJAXTest;

/**
 * Tests the click sorting AJAX functionality of Views exposed forms.
 *
 * @group Pagerer
 */
class CorePagerReplacePaginationAJAXTest extends PaginationAJAXTest {

  protected $pagererAdmin = 'admin/config/user-interface/pagerer';

  /**
   * {@inheritdoc}
   */
  public static $modules = ['node', 'views', 'views_test_config', 'pagerer'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $edit = [
      'label' => 'core_replace',
      'id' => 'core_replace',
    ];
    $this->drupalPostForm($this->pagererAdmin . '/preset/add', $edit, 'Create');
    $edit = [
      'core_override_preset' => 'core_replace',
    ];
    $this->drupalPostForm($this->pagererAdmin, $edit, 'Save configuration');
  }

}
