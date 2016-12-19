<?php

namespace Drupal\pagerer\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Checks Pagerer functionality.
 *
 * @group Pagerer
 */
class PagererTest extends WebTestBase {

  protected $pagererAdmin = 'admin/config/user-interface/pagerer';

  public static $modules = array('dblog', 'pagerer', 'pagerer_example');

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Insert 300 log messages.
    $logger = $this->container->get('logger.factory')->get('pager_test');
    for ($i = 0; $i < 300; $i++) {
      $logger->debug($this->randomString());
    }

    $this->drupalLogin($this->drupalCreateUser([
      'access site reports',
      'administer site configuration',
    ]));
  }

  /**
   * Tests Pagerer functionality.
   */
  public function testPagerer() {
    // Admin UI tests.
    $edit = array(
      'label' => 'ui_test',
      'id' => 'ui_test',
    );
    $this->drupalPostForm($this->pagererAdmin . '/preset/add', $edit, 'Create');
    $edit = array(
      'core_override_preset' => 'ui_test',
    );
    $this->drupalPostForm($this->pagererAdmin, $edit, 'Save configuration');
    $styles = [
      'standard',
      'none',
      'basic',
      'progressive',
      'adaptive',
      'mini',
      'slider',
      'scrollpane',
    ];
    foreach ($styles as $style) {
      $this->drupalGet($this->pagererAdmin . '/preset/manage/ui_test');
      $edit = [
        'panes_container[left][style]' => 'none',
        'panes_container[center][style]' => 'none',
        'panes_container[right][style]' => $style,
      ];
      $this->drupalPostForm(NULL, $edit, 'Save');
      $this->drupalGet($this->pagererAdmin . '/preset/manage/ui_test');
      if ($style !== 'none') {
        $this->drupalPostForm(NULL, $edit, 'Reset');
        $this->drupalPostForm(NULL, [], 'Reset');
        $this->assertNoRaw('fooxiey');
        $this->drupalPostForm(NULL, $edit, 'Configure');
        $edit = [
          'prefix_display' => '1',
          'tags_container[pages][prefix_label]' => 'fooxiey',
        ];
        $this->drupalPostForm(NULL, $edit, 'Save');
        $this->assertRaw('fooxiey');
      }
    }

    // Load example page.
    $this->drupalGet('pagerer/example');
  }

}
