<?php

namespace Drupal\pagerer\Tests;

use Drupal\system\Tests\Pager\PagerTest;
use Drupal\simpletest\WebTestBase;

/**
 * Test replacement of Drupal core pager.
 *
 * @group Pagerer
 */
class CorePagerReplaceTest extends PagerTest {

  protected $pagererAdmin = 'admin/config/user-interface/pagerer';

  public static $modules = array('dblog', 'pagerer');

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    WebTestBase::setUp();

    // Insert 300 log messages.
    $logger = $this->container->get('logger.factory')->get('pager_test');
    for ($i = 0; $i < 300; $i++) {
      $logger->debug($this->randomString());
    }

    $this->drupalLogin($this->drupalCreateUser([
      'access site reports',
      'administer site configuration',
    ]));

    $edit = array(
      'label' => 'core_replace',
      'id' => 'core_replace',
    );
    $this->drupalPostForm($this->pagererAdmin . '/preset/add', $edit, 'Create');
    $edit = array(
      'core_override_preset' => 'core_replace',
    );
    $this->drupalPostForm($this->pagererAdmin, $edit, 'Save configuration');
  }

  /**
   * Test that pagerer specific cache tags have been added.
   */
  protected function testPagerQueryParametersAndCacheContext() {
    parent::testPagerQueryParametersAndCacheContext();
    $this->assertCacheTag('config:pagerer.settings');
    $this->assertCacheTag('config:pagerer.preset.core_replace');
  }

}
