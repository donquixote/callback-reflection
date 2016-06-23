<?php

namespace Donquixote\CallbackReflection\Util;

final class CodegenUtil extends UtilBase {

  /**
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function argsPhpGetArglistPhp(array $argsPhp) {
    if (array() === $argsPhp) {
      return '';
    }
    else {
      return "\n  " . self::indent(implode(",\n", $argsPhp), '  ');
    }
  }

  /**
   * @param string $php
   * @param string $indentation
   *
   * @return mixed
   */
  public static function indent($php, $indentation) {
    $tokens = token_get_all('<?php' . "\n" . $php);
    array_shift($tokens);
    $out = '';
    foreach ($tokens as $token) {
      if (is_string($token)) {
        $out .= $token;
      }
      elseif ($token[0] !== T_WHITESPACE && $token[0] !== T_DOC_COMMENT && $token[0] !== T_COMMENT) {
        $out .= $token[1];
      }
      else {
        $out .= str_replace("\n", "\n" . $indentation, $token[1]);
      }
    }
    return $out;
  }
}
