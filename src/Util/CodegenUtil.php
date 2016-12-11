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

    $oneline = implode(', ', $argsPhp);
    if (FALSE === strpos($oneline, "\n") && strlen($oneline) < 30) {
      return $oneline;
    }

    return "\n" . implode(",\n", $argsPhp);
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

  /**
   * @param string $php
   * @param string $indent_level
   * @param string $indent_base
   *
   * @return string
   */
  public static function autoIndent($php, $indent_level, $indent_base = '') {
    $tokens = token_get_all('<?php' . "\n" . $php);
    $tokens[] = [T_WHITESPACE, "\n"];
    $tokens[] = '#';
    $tokens = self::prepareTokens($tokens);

    $i = 1;
    $out = [''];
    self::doAutoIndent($out, $tokens, $i, $indent_base, $indent_level);

    array_pop($out);

    return implode('', $out);
  }

  /**
   * @param array $tokens_original
   *
   * @return array
   */
  private static function prepareTokens(array $tokens_original) {

    $tokens_prepared = [];
    for ($i = 0; TRUE; ++$i) {
      $token = $tokens_original[$i];
      if (T_COMMENT === $token[0]) {
        if ("\n" === substr($token[1], -1)) {
          $tokens_prepared[] = [T_COMMENT, substr($token[1], 0, -1)];
          if (T_WHITESPACE === $tokens_original[$i + 1][0]) {
            $tokens_prepared[] = [T_WHITESPACE, "\n" . $tokens_original[$i + 1][1]];
            ++$i;
          }
          else {
            $tokens_prepared[] = [T_WHITESPACE, "\n"];
          }
          continue;
        }
      }

      $tokens_prepared[] = $token;

      if ('#' === $token) {
        break;
      }
    }

    return $tokens_prepared;
  }

  /**
   * @param string[] $out
   * @param array $tokens
   * @param int $i
   * @param string $indent_base
   * @param string $indent_level
   */
  private static function doAutoIndent(array &$out, array $tokens, &$i, $indent_base, $indent_level) {

    $indent_deeper = $indent_base . $indent_level;

    while (TRUE) {
      $token = $tokens[$i];

      if (is_string($token)) {

        switch ($token) {

          case '{':
          case '(':
          case '[':
            $out[] = $token;
            ++$i;
            self::doAutoIndent($out, $tokens, $i, $indent_deeper, $indent_level);
            if ('#' === $tokens[$i]) {
              return;
            }
            if (T_WHITESPACE === $tokens[$i - 1][0]) {
              $out[$i - 1] = str_replace($indent_deeper, $indent_base, $out[$i - 1]);
            }
            break;

          case '}':
          case ')':
          case ']':
            $out[] = $token;
            return;

          case '#':
            return;

          default:
            $out[] = $token;
            break;
        }
      }
      else {
        switch ($token[0]) {

          case T_WHITESPACE:
            $n_linebreaks = substr_count($token[1], "\n");
            if (0 === $n_linebreaks) {
              $out[] = $token[1];
              ++$i;
              continue 2;
            }
            $out[] = str_repeat("\n", $n_linebreaks) . $indent_base;
            break;

          case T_COMMENT:
          case T_DOC_COMMENT:
            # $out[] = $token[1];
            $out[] = preg_replace("@ *\\n *\\*@", "\n" . $indent_base . ' *', $token[1]);
            break;

          default:
            $out[] = $token[1];
            break;
        }
      }

      ++$i;
    }
  }
}
