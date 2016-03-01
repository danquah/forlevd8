<?php
/**
 * @file
 * Contains \Drupal\paragraphs_demo\Tests\ParagraphsDemoTest.php.
 */

namespace Drupal\paragraphs_demo\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests the demo module for Paragraphs.
 *
 * @group paragraphs
 */
class ParagraphsDemoTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var string[]
   */
  public static $modules = array(
    'paragraphs_demo',
    'block',
  );

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->drupalPlaceBlock('local_tasks_block');
    $this->drupalPlaceBlock('local_actions_block');
    $this->drupalPlaceBlock('page_title_block');
  }

  /**
   * Asserts demo paragraphs have been created.
   */
  protected function testConfigurationsAndCreation() {
    $admin_user = $this->drupalCreateUser(array(
      'administer site configuration',
      'administer nodes',
      'create paragraphed_content_demo content',
      'edit any paragraphed_content_demo content',
      'delete any paragraphed_content_demo content',
      'administer content translation',
      'create content translations',
      'administer languages',
      'administer content types',
      'administer node fields',
      'administer node display',
      'administer paragraphs types',
      'administer paragraph fields',
      'administer paragraph display',
      'administer paragraph form display',
      'administer node form display',
    ));

    $this->drupalLogin($admin_user);
    // Check for all pre-configured paragraphs_types.
    $this->drupalGet('admin/structure/paragraphs_type');
    $this->assertText('Image + Text');
    $this->assertText('Images');
    $this->assertText('Text');
    $this->assertText('Text + Image');
    $this->assertText('User');

    // Check for preconfigured languages.
    $this->drupalGet('admin/config/regional/language');
    $this->assertText('English');
    $this->assertText('German');
    $this->assertText('French');

    // Check for Content language translation checks.
    $this->drupalGet('admin/config/regional/content-language');
    $this->assertFieldChecked('edit-entity-types-node');
    $this->assertFieldChecked('edit-entity-types-paragraph');
    $this->assertFieldChecked('edit-settings-node-paragraphed-content-demo-translatable');
    $this->assertNoFieldChecked('edit-settings-node-paragraphed-content-demo-fields-field-paragraphs-demo');
    $this->assertFieldChecked('edit-settings-paragraph-images-translatable');
    $this->assertFieldChecked('edit-settings-paragraph-image-text-translatable');
    $this->assertFieldChecked('edit-settings-paragraph-text-translatable');
    $this->assertFieldChecked('edit-settings-paragraph-text-image-translatable');
    $this->assertFieldChecked('edit-settings-paragraph-user-translatable');

    // Check for paragraph type Image + text that has the correct fields set.
    $this->drupalGet('admin/structure/paragraphs_type/image_text/fields');
    $this->assertText('Text');
    $this->assertText('Image');

    // Check for paragraph type Text that has the correct fields set.
    $this->drupalGet('admin/structure/paragraphs_type/text/fields');
    $this->assertText('Text');
    $this->assertNoText('Image');

    // Make sure we have the paragraphed article listed as a content type.
    $this->drupalGet('admin/structure/types');
    $this->assertText('Paragraphed article');

    // Check that title and the descriptions are set.
    $this->drupalGet('admin/structure/types/manage/paragraphed_content_demo');
    $this->assertText('Paragraphed article');
    $this->assertText('Article with paragraphs.');

    // Check that the Paragraph field is added.
    $this->clickLink('Manage fields');
    $this->assertText('Paragraphs');

    // Check that all paragraphs types are enabled (disabled).
    $this->clickLink('Edit', 0);
    $this->assertNoFieldChecked('edit-settings-handler-settings-target-bundles-drag-drop-image-text-enabled');
    $this->assertNoFieldChecked('edit-settings-handler-settings-target-bundles-drag-drop-images-enabled');
    $this->assertNoFieldChecked('edit-settings-handler-settings-target-bundles-drag-drop-text-image-enabled');
    $this->assertNoFieldChecked('edit-settings-handler-settings-target-bundles-drag-drop-user-enabled');
    $this->assertNoFieldChecked('edit-settings-handler-settings-target-bundles-drag-drop-text-enabled');

    $this->drupalGet('node/add/paragraphed_content_demo');
    $this->drupalPostForm(NULL, NULL, t('Add Text'));
    $edit = array(
      'title[0][value]' => 'Paragraph title',
      'field_paragraphs_demo[0][subform][field_text_demo][0][value]' => 'Paragraph text',
    );
    $this->drupalPostForm(NULL, $edit, t('Save and publish'));

    $this->assertText('Paragraph title');
    $this->assertText('Paragraph text');
  }

}
