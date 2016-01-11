<?php

namespace Donquixote\CallbackReflection\Callback;

class CallbackReflection_Function implements CallbackReflectionInterface {

  /**
   * @var \ReflectionFunction
   */
  private $reflFunction;

  /**
   * @param \ReflectionFunction $reflFunction
   */
  function __construct(\ReflectionFunction $reflFunction) {
    $this->reflFunction = $reflFunction;
  }

  /**
   * @return \ReflectionParameter[]
   */
  function getReflectionParameters() {
    return $this->reflFunction->getParameters();
  }

  /**
   * @param mixed[] $args
   *
   * @return mixed|void
   */
  function invokeArgs(array $args) {
    return $this->reflFunction->invokeArgs($args);
  }
}
