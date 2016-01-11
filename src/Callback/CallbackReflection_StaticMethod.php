<?php

namespace Donquixote\CallbackReflection\Callback;

class CallbackReflection_StaticMethod implements CallbackReflectionInterface {

  /**
   * @var \ReflectionMethod
   */
  private $reflMethod;

  /**
   * @param \ReflectionMethod $reflMethod
   */
  function __construct(\ReflectionMethod $reflMethod) {
    $this->reflMethod = $reflMethod;
  }

  /**
   * @param string $className
   * @param string $methodName
   *
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod
   */
  static function create($className, $methodName) {
    $reflectionMethod = new \ReflectionMethod($className, $methodName);
    return new self($reflectionMethod);
  }

  /**
   * @return \ReflectionParameter[]
   */
  function getReflectionParameters() {
    return $this->reflMethod->getParameters();
  }

  /**
   * @param mixed[] $args
   *
   * @return mixed|void
   */
  function invokeArgs(array $args) {
    return $this->reflMethod->invokeArgs(NULL, $args);
  }
}
