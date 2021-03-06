<?php

namespace Donquixote\CallbackReflection\Callback;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelperInterface;

interface CallbackReflectionInterface {

  /**
   * Gets the parameters as native \ReflectionParameter objects.
   *
   * @return \ReflectionParameter[]
   *
   * @see \ReflectionFunctionAbstract::getParameters()
   */
  public function getReflectionParameters();

  /**
   * @param mixed[] $args
   *
   * @return mixed|void
   *
   * @throws \Exception
   */
  public function invokeArgs(array $args);

  /**
   * @param string[] $argsPhp
   *   PHP statements for each parameter.
   * @param \Donquixote\CallbackReflection\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement.
   */
  public function argsPhpGetPhp(array $argsPhp, CodegenHelperInterface $helper);

}
