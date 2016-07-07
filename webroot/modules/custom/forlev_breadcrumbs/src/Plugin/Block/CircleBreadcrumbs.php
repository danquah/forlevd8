<?php
/**
 * @file
 * Contains \Drupal\yourmodule\Plugin\Block\YourBlockName.
 */

namespace Drupal\forlev_breadcrumbs\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Provides my custom block.
 *
 * @Block(
 *   id = "forlev_breadcrumbs_block",
 *   admin_label = @Translation("Forlev circle breadcrumb"),
 *   category = @Translation("Blocks")
 * )
 */
class CircleBreadcrumbs extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $bla = 123;
    return array('#markup' => 'hello world');
  }
}
