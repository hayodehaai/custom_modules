<?php

namespace Drupal\turn\Plugin\Filter;

use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * A filter to turn the last- and first name.
 *
 * This filter changes the token "[name:FIRSTNAME:LASTNAME]".
 * To "Name: LASTNAME FIRSTNAME".
 */

/**
 * This creates the checkbox in the editor configuration.
 *
 * @Filter(
 *   id = "filter_turn",
 *   title = @Translation("Turn Name"),
 *   description = @Translation("Turn first- and last name in token [name:FIRSTNAME:LASTNAME]"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE,
 * )
 */
class TurnName extends FilterBase {

  /**
   * Filter function to replace the token.
   */
  public function process($text, $langcode) {
    // Pattern to search for: [name:anything:anything].
    $pattern = '/\[name:(?:[^\:]*):(?:[^\:]*)\]/';
    // Scan for token.
    preg_match_all($pattern, $text, $match);
    foreach ($match[0] as $value => $names) {
      // Store result in array.
      $name = explode(":", (str_replace(["[name:", "]"], "", $names)));
      $replace = $this->t("Name: @lastname @firstname", ["@firstname" => $name[0], "@lastname" => $name[1]]);
      // Replace token.
      $token_replaced = str_replace($names, $replace, $text);
      /* Replace the next token in the text that's left,
       * so that not only the first token is continously replaced.
       */
      $text = $token_replaced;
    }
    $result = new FilterProcessResult($text);
    return $result;
  }

}
