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
  public function doBreak($context) {
    $this->breakPoint(1 === func_num_args() ? $context : func_get_arg(1));
  }

  /**
   * Do break.
   */
  protected function breakPoint($twig_context) {
    if (TRUE) {
      // Place breakpoint on the next line.
      return;
    }
  }

}
