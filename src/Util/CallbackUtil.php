<?php

namespace Donquixote\CallbackReflection\Util;

use Donquixote\CallbackReflection\Callback\CallbackReflection_Function;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;

final class CallbackUtil extends UtilBase {

  /**
   * @param mixed|callable $callable
   *
   * @return null|\Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  static function callableGetCallback($callable) {

    if (!is_callable($callable)) {
      return NULL;
    }

    if (is_string($callable)) {
      if (FALSE === strpos($callable, '::')) {
        if (!function_exists($callable)) {
          return NULL;
        }
        $reflFunction = new \ReflectionFunction($callable);
        return new CallbackReflection_Function($reflFunction);
      }
      list($classOrObject, $methodName) = explode('::', $callable);
    }
    elseif (is_object($callable)) {
      if (!method_exists($callable, '__invoke')) {
        return NULL;
      }
      $classOrObject = $callable;
      $methodName = '__invoke';
    }
    elseif (!is_array($callable)) {
      return NULL;
    }
    elseif (!isset($callable[0]) || !isset($callable[1])) {
      return NULL;
    }
    else {
      list($classOrObject, $methodName) = $callable;
    }

    if (!method_exists($classOrObject, $methodName)) {
      return NULL;
    }

    if (is_object($classOrObject)) {
      return NULL;
    }

    $reflMethod = new \ReflectionMethod($classOrObject, $methodName);

    return new CallbackReflection_StaticMethod($reflMethod);
  }

}
