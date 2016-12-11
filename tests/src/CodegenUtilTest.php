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

}
