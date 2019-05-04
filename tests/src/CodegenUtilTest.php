<?php

namespace Donquixote\CallbackReflection\Tests;

use Donquixote\CallbackReflection\Util\CodegenUtil;
use PHPUnit\Framework\TestCase;

class CodegenUtilTest extends TestCase {

  public function testIndent() {

    self::customAssertSame(
      <<<'EOT'

  foo(
    5,
    'A
B');
EOT
      ,
      CodegenUtil::indent(
        <<<'EOT'

foo(
  5,
  'A
B');
EOT
        ,
        '  '
      )
    );

    self::customAssertSame(
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
      CodegenUtil::indent(
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
        '  '
      )
    );
  }

  public function testArgsPhpGetArglistPhp() {

    self::customAssertSame(
      '
new \stdClass,
5,
"x",
foo(
  4,
  5),
\'A
B\'',
      CodegenUtil::argsPhpGetArglistPhp(
        [
          'new \stdClass',
          '5',
          '"x"',
          "foo(\n  4,\n  5)",
          var_export("A\nB", TRUE),
        ]
      )
    );

    self::customAssertSame('', CodegenUtil::argsPhpGetArglistPhp([]));
  }

  public function testAliasify() {

    $php = <<<'EOT'

class C {
  function foo() {
    new \Animal\Dog;
    new Animal\Cat();
    new Food();
    new Food\Catfood;
    Food::create();
    \Food::class;
    \Food\Catfood::prepare();
    Food\Dogfood::feedTheDog();
    Competition\Catfood::otherAlias();
  }
}
EOT;

    $php_expected = <<<'EOT'

class C {
  function foo() {
    new Dog;
    new Cat();
    new \Food();
    new Catfood;
    \Food::create();
    \Food::class;
    Catfood::prepare();
    Dogfood::feedTheDog();
    Catfood_1::otherAlias();
  }
}
EOT;

    $aliases_expected = [
      'Animal\Cat' => true,
      'Animal\Dog' => true,
      'Competition\Catfood' => 'Catfood_1',
      'Food\Catfood' => true,
      'Food\Dogfood' => true,
    ];

    $aliases = CodegenUtil::aliasify($php);

    self::customAssertSame($php_expected, $php);
    self::customAssertSame($aliases_expected, $aliases);
  }

  public function testAutoIndent() {

    $ugly = <<<'EOT'

 class C {
  
 /**
   * @return string
      */
function foo() {

// Inline comment.
print 'foo';

      // Another inline comment.
          print 'bar';

/*
* Non-doc comment.
*/
return 'a
b';
}
}
EOT;
    $clean = <<<'EOT'

class C {

  /**
   * @return string
   */
  function foo() {

    // Inline comment.
    print 'foo';

    // Another inline comment.
    print 'bar';

    /*
     * Non-doc comment.
     */
    return 'a
b';
  }
}
EOT;
    self::customAssertSame($clean, CodegenUtil::autoIndent($ugly, '  '));
  }

  /**
   * Override of assertSame() that makes spaces visible in the diff.
   *
   * @param mixed $expected
   * @param mixed $actual
   * @param string $message
   */
  public static function customAssertSame($expected, $actual, $message = '') {

    if (!\is_string($expected) || !\is_string($actual)) {
      self::assertSame($expected, $actual, $message);
    }

    // Check if the actual code has a diff.
    $expected_despaced = str_replace(' ', '', $expected);
    $actual_despaced = str_replace(' ', '', $actual);
    if ($expected_despaced !== $actual_despaced) {
      self::assertSame($expected, $actual, $message);
    }

    // Make spaces visible.
    $expected_processed = str_replace("\n", "\\n\n", $expected);
    $actual_processed = str_replace("\n", "\\n\n", $actual);
    if ($expected_processed === $actual_processed) {
      self::assertSame($expected, $actual, $message);
    }

    self::assertSame($expected_processed, $actual_processed, $message);
  }

}
