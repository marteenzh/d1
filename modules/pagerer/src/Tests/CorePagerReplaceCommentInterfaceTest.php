<?php

namespace Drupal\pagerer\Tests;

use Drupal\comment\Tests\CommentInterfaceTest;

/**
 * Test replacement of Drupal core pager for Comment interface.
 *
 * @group Pagerer
 */
class CorePagerReplaceCommentInterfaceTest extends CommentInterfaceTest {

  protected $pagererAdmin = 'admin/config/user-interface/pagerer';

  public static $modules = array('dblog', 'pagerer', 'comment');

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

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
    $this->drupalLogout();
  }

}
