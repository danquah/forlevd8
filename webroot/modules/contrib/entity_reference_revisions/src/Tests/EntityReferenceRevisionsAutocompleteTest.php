<?php
/**
 * @file
 * Contains \Drupal\entity_reference_revisions\EntityReferenceRevisionsAutocompleteTest.
 *
 * entity_reference_revisions configuration test functions.
 *
 */

namespace Drupal\entity_reference_revisions\Tests;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\field_ui\Tests\FieldUiTestTrait;
use Drupal\simpletest\WebTestBase;

/**
 * Tests the entity_reference_revisions autocomplete.
 *
 * @group entity_reference_revisions
 */
class EntityReferenceRevisionsAutocompleteTest extends WebTestBase {

  use FieldUiTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array(
    'block_content',
    'node',
    'field',
    'entity_reference_revisions',
    'field_ui',
  );

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    // Create article content type.
    $this->drupalCreateContentType(array('type' => 'article', 'name' => 'Article'));
    // Place the breadcrumb, tested in fieldUIAddNewField().
    $this->drupalPlaceBlock('system_breadcrumb_block');
  }

  /**
   * Test for autocomplete processing.
   *
   * Tests that processing does not crash when the entity types of the
   * referenced entity and of the entity the field is attached to are different.
   */
  public function testEntityReferenceRevisionsAutocompleteProcessing() {
    $admin_user = $this->drupalCreateUser(array(
      'administer site configuration',
      'administer nodes',
      'administer blocks',
      'create article content',
      'administer content types',
      'administer node fields',
      'administer node display',
      'administer node form display',
      'edit any article content',
    ));
    $this->drupalLogin($admin_user);

    // Create a custom block content bundle.
    $this->createBlockContentType(array('type' => 'customblockcontent', 'name' => 'Custom Block Content'));

    // Create entity reference revisions field attached to article.
    static::fieldUIAddNewField(
      'admin/structure/types/manage/article',
      'entity_reference_revisions',
      'Entity reference revisions',
      'entity_reference_revisions',
      array('settings[target_type]' => 'block_content', 'cardinality' => '-1'),
      array('settings[handler_settings][target_bundles][customblockcontent]' => TRUE)
    );

    // Create custom block.
    $block_label = $this->randomMachineName();
    $block_content = $this->randomString();
    $edit = array(
      'info[0][value]' => $block_label,
      'body[0][value]' => $block_content,
      'revision' => TRUE,
    );
    $this->drupalPostForm('block/add', $edit, t('Save'));
    $block = $this->drupalGetBlockByInfo($block_label);

    // Create an article.
    $title = $this->randomMachineName();
    $edit = array(
      'title[0][value]' => $title,
      'body[0][value]' => 'Revision 1',
      'field_entity_reference_revisions[0][target_id]' => $block_label . '(' . $block->id() . ')',
      'revision' => TRUE,
    );
    $this->drupalPostForm('node/add/article', $edit, t('Save and publish'));
    $this->assertText($title);
    $this->assertText(SafeMarkup::checkPlain($block_content));
  }

  /**
   * Get a custom block from the database based on its title.
   *
   * @param $info
   *   A block title, usually generated by $this->randomMachineName().
   * @param $reset
   *   (optional) Whether to reset the entity cache.
   *
   * @return \Drupal\block\BlockInterface
   *   A block entity matching $info.
   */
  function drupalGetBlockByInfo($info, $reset = FALSE) {
    if ($reset) {
      \Drupal::entityManager()->getStorage('block_content')->resetCache();
    }
    $blocks = entity_load_multiple_by_properties('block_content', array('info' => $info));
    // Get the first block returned from the database.
    $returned_block = reset($blocks);
    return $returned_block;
  }

  /**
   * Create a block_content bundle.
   *
   * @param $parameters
   *   An assoc array with name (human readable) and type (bundle machine name)
   *   as keys.
   */
  function createBlockContentType($parameters) {
    $label = $parameters['name'];
    $machine_name = $parameters['type'];
    $edit = array(
      'label' => $label,
      'id' => $machine_name,
      'revision' => TRUE,
    );
    $this->drupalPostForm('admin/structure/block/block-content/types/add', $edit, t('Save'));
    $this->assertText($label);
  }

}
