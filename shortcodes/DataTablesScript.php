<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class DataTablesScript extends Shortcode
{
  public function init()
  {
    $this->shortcode->getHandlers()->add('dt-script', function(ShortcodeInterface $sc) {
      $content = trim($sc->getContent());
      while ( preg_match('/\<[^>]*\>/', $content, $matches))  { // strip tags from both ends
        $tag = strlen($matches[0]);
        $len = strlen($content);
        $content = substr($content,$tag,$len-$tag-$tag-1);
      }
      if ( isset($this->grav['datatables'] )) $this->grav['datatables'] .= $content;
      return '';
    });
  }
}
