<?php
namespace Drupal\forlev_base;

use Drupal\Core\Template\TwigExtension;

class ForlevTwigExtension extends TwigExtension{
  public function getFunctions() {
    return [new \Twig_SimpleFunction('xdebug_break', [$this, 'doBreak'], ['needs_context' => true])];
  }

  public function getName() {
    return 'forlev_debug';
  }

  public function doBreak($context) {
    $this->breakPoint(1 === func_num_args() ? $context : func_get_arg(1));
  }

  protected function breakPoint($twig)
  {
    return;
  }



}
