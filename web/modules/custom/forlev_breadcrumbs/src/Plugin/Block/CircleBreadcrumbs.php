<?php
namespace Drupal\forlev_breadcrumbs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Menu\MenuActiveTrailInterface;
use Drupal\Core\Menu\MenuLinkManagerInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\system\Plugin\Block\SystemBreadcrumbBlock;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides my custom block.
 *
 * @Block(
 *   id = "forlev_breadcrumbs_block",
 *   admin_label = @Translation("Forlev circle breadcrumb"),
 *   category = @Translation("Blocks")
 * )
 */
class CircleBreadcrumbs extends BlockBase implements ContainerFactoryPluginInterface {

  /** @var MenuLinkManagerInterface  */
  protected $linkManager;

  /** @var MenuActiveTrailInterface  */
  protected $active;

  /** @var  MenuLinkTreeInterface */
  protected $linkTree;

  /**
   * CircleBreadcrumbs constructor.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MenuLinkManagerInterface $link, MenuActiveTrailInterface $active, $link_tree) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->linkManager = $link;
    $this->active = $active;
    $this->linkTree = $link_tree;
  }


  /**
   * {@inheritdoc}
   */
  public function build() {
    $menu_tree_parameters = new MenuTreeParameters();
    $menu_tree_parameters->minDepth = 1;
    $menu_tree_parameters->maxDepth = 4;

    $tree = $this->linkTree->load('main', $menu_tree_parameters);
    $ra = empty($tree) ? '' : $this->linkTree->build($tree);
    if (isset($ra['#theme'])) {
      $ra['#theme'] = 'menu__circlebreadcrumbs';
    }
    return $ra;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array                                                     $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string                                                    $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed                                                     $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.menu.link'),
      $container->get('menu.active_trail'),
      $container->get('menu.link_tree')
    );
  }
}
