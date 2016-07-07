<?php
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
    return array('#markup' => 'hello world');
  }

}
