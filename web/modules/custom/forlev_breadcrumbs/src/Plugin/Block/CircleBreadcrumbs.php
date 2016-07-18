<?php
namespace Drupal\forlev_breadcrumbs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\system\Plugin\Block\SystemBreadcrumbBlock;

/**
 * Provides my custom block.
 *
 * @Block(
 *   id = "forlev_breadcrumbs_block",
 *   admin_label = @Translation("Forlev circle breadcrumb"),
 *   category = @Translation("Blocks")
 * )
 */
class CircleBreadcrumbs extends SystemBreadcrumbBlock {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return $this->breadcrumbManager->build($this->routeMatch)->toRenderable();
  }

}
