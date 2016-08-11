<?php
namespace Drupal\forlev_base;

use Drupal\Core\Template\TwigExtension;

/**
 * Class ForlevTwigExtension.
 */
class ForlevTwigExtension extends TwigExtension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction(
        'xdebug_break',
        [$this, 'doBreak'],
        ['needs_context' => TRUE]
      ),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'forlev_debug';
  }

  /**
   * Prepare to do breakpoint.
   */
  public function doBreak($context, ...$args) {
    $this->breakPoint($context, $args);
  }

  /**
   * Do break.
   */
  protected function breakPoint($twig_context, $args) {
    if (TRUE) {
      // Place breakpoint on the next line.
      return;
    }
  }

}
