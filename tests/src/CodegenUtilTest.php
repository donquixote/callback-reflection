<?php

namespace Donquixote\CallbackReflection\Tests;

use Donquixote\CallbackReflection\Util\CodegenUtil;

class CodegenUtilTest extends \PHPUnit_Framework_TestCase {

  public function testIndent() {

    static::assertSame(
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
      CodegenUtil::argsPhpGetArglistPhp(
        array(
          'new \stdClass',
          '5',
          '"x"',
          "foo(\n  4,\n  5)",
          var_export("A\nB", TRUE),
        )
      )
    );

    static::assertSame('', CodegenUtil::argsPhpGetArglistPhp(array()));
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
    static::assertSame($clean, CodegenUtil::autoIndent($ugly, '  '));
  }

  /**
   * Override of assertSame() that makes spaces visible in the diff.
   *
   * @param mixed $expected
   * @param mixed $actual
   * @param string $message
   */
  public static function assertSame($expected, $actual, $message = '') {

    if (!is_string($expected) || !is_string($actual)) {
      parent::assertSame($expected, $actual, $message);
    }

    // Check if the actual code has a diff.
    $expected_despaced = str_replace(' ', '', $expected);
    $actual_despaced = str_replace(' ', '', $actual);
    if ($expected_despaced !== $actual_despaced) {
      parent::assertSame($expected, $actual, $message);
    }

    // Make spaces visible.
    $expected_processed = str_replace("\n", "\\n\n", $expected);
    $actual_processed = str_replace("\n", "\\n\n", $actual);
    if ($expected_processed === $actual_processed) {
      parent::assertSame($expected, $actual, $message);
    }

    parent::assertSame($expected_processed, $actual_processed, $message);
  }

}
