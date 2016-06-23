<?php

namespace Donquixote\CallbackReflection\Tests;

use Donquixote\CallbackReflection\Util\ParamUtil;

class ParamUtilTest extends \PHPUnit_Framework_TestCase {

  public function testParamsValidateArgs() {
    $params = (new \ReflectionMethod(ParamUtilTest_C::class, 'foo'))->getParameters();

    static::assertTrue(
      ParamUtil::paramsValidateArgs(
        $params,
        array(
          new \stdClass,
          new ParamUtilTest_D(),
        )));

    static::assertFalse(
      ParamUtil::paramsValidateArgs(
        $params,
        array(
          new \stdClass,
          new ParamUtilTest_D(),
          5,
        )));

    static::assertFalse(
      ParamUtil::paramsValidateArgs(
        $params,
        array(
          new ParamUtilTest_D(),
          new \stdClass,
        )));

    static::assertFalse(
      ParamUtil::paramsValidateArgs(
        $params,
        array(
          new \stdClass,
        )));

    static::assertFalse(
      ParamUtil::paramsValidateArgs(
        $params,
        array(
          'x',
          'y',
        )));
  }

  public function testIndent() {

    static::assertSame(
      <<<'EOT'

  foo(
    5,
    'A
B');
EOT
      ,
      ParamUtil::indent(
        <<<'EOT'

foo(
  5,
  'A
B');
EOT
        ,
        '  '));

    static::assertSame(
      <<<'EOT'
/**
   * @return string
   */
  function foo() {
    /*
     * Non-doc comment.
     */
    return 'a
b';
  }
EOT
      ,
      ParamUtil::indent(
        <<<'EOT'
/**
 * @return string
 */
function foo() {
  /*
   * Non-doc comment.
   */
  return 'a
b';
}
EOT
        ,
        '  '));
  }

  public function testArgsPhpGetArglistPhp() {

    static::assertSame(
      '
  new \stdClass,
  5,
  "x",
  foo(
    4,
    5),
  \'A
B\'',
      ParamUtil::argsPhpGetArglistPhp(
        array(
          'new \stdClass',
          '5',
          '"x"',
          "foo(\n  4,\n  5)",
          var_export("A\nB", true),
        )));

    static::assertSame('', ParamUtil::argsPhpGetArglistPhp(array()));
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
