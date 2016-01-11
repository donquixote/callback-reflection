<?php

namespace Donquixote\CallbackReflection\Callback;

/**
 * Wraps a class constructor as a factory callback.
 */
class CallbackReflection_ClassConstruction implements CallbackReflectionInterface {

  /**
   * @var \ReflectionClass
   */
  private $reflClass;

  /**
   * @param $class
   *
   * @return null|static
   */
  static function createFromClassNameCandidate($class) {
    return class_exists($class)
      ? new static(new \ReflectionClass($class))
      : NULL;
  }

  /**
   * @param string $class
   *
   * @return static
   */
  static function createFromClassName($class) {
    return new static(new \ReflectionClass($class));
  }

  /**
   * @param \ReflectionClass $reflClass
   */
  function __construct(\ReflectionClass $reflClass) {
    $this->reflClass = $reflClass;
  }

  /**
   * @return \ReflectionParameter[]
   */
  function getReflectionParameters() {
    $reflConstructor = $this->reflClass->getConstructor();
    if (NULL === $reflConstructor) {
      return array();
    }
    return $reflConstructor->getParameters();
  }

  /**
   * @param mixed[] $args
   *
   * @return object
   */
  function invokeArgs(array $args) {
    return $this->reflClass->newInstanceArgs($args);
  }
}
