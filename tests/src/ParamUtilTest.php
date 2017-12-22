<?php

namespace Donquixote\CallbackReflection\Tests;

use Donquixote\CallbackReflection\Util\ParamUtil;

class ParamUtilTest extends \PHPUnit_Framework_TestCase {

  public function testParamsValidateArgs() {
    $params = (new \ReflectionMethod(ParamUtilTest_C::class, 'foo'))->getParameters();

    static::assertTrue(
      ParamUtil::paramsValidateArgs(
        $params,
        [
          new \stdClass,
          new ParamUtilTest_D(),
        ]));

    static::assertFalse(
      ParamUtil::paramsValidateArgs(
        $params,
        [
          new \stdClass,
          new ParamUtilTest_D(),
          5,
        ]));

    static::assertFalse(
      ParamUtil::paramsValidateArgs(
        $params,
        [
          new ParamUtilTest_D(),
          new \stdClass,
        ]));

    static::assertFalse(
      ParamUtil::paramsValidateArgs(
        $params,
        [
          new \stdClass,
        ]));

    static::assertFalse(
      ParamUtil::paramsValidateArgs(
        $params,
        [
          'x',
          'y',
        ]));
  }

}

class ParamUtilTest_C {

  public static function foo(\stdClass $c, ParamUtilTest_I $i) {
    // Nothing.
  }
}

class ParamUtilTest_D implements ParamUtilTest_I {

}

interface ParamUtilTest_I {

}
