<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Grav\Common\Utils;

class DataTablesShortcode extends Shortcode
{
  public function init()
  {
    $this->shortcode->getHandlers()->add('datatables', function(ShortcodeInterface $sc) {
      $content = $sc->getContent();
      $parameters = $sc->getParameters();
      $id = '';
      $res = preg_match('/\<table[^>]*?\>(.*)\<\/table[^>]*\>/ims',$content);
      // does table have a table attached?
      if ( $res === FALSE or $res == 0) {
        // error some where
        return $this->twig->processTemplate('partials/datatables-error.html.twig',
        [ 'message' => 'Shortcode content does not appear to have a valid &lt;table&gt;...&lt;/table&gt; element. Got instead:',
          'content' => $content
        ] );
      } else {
        $res = preg_match('/(\<table\s[^>]*)id="(.*?)"(.*\>)/ims',$content,$matches);
        if ( $res == 1 ) {
          $this->grav['debugger']->addMessage('found id');
          if ( ! $matches[2] || preg_match( '/\s/',$matches[2]) == 1 ) {
            // id either has spaces - illegal - or is null, so strip output
            $content = $matches[1] . $matches[3];
          } else {
              $id = $matches[2];
            }
          }
      }
      if ( ! $id ) {
        if ( isset( $params['grav-id'])) {
            $id = trim($params['grav-id']);
            unset($params['grav-id']);
            if (preg_match('/\s/')) { $id = '';} // ignore an illegal id
        }
        // this occurs if content has <table without an id, or an illegal id has be stripped out
        if (! $id ) $id = Utils::generateRandomString(10);
        $pos = stripos('<table', $content);
        $end = substr($content,7);
        $content = substr_replace($content," id=\"$id\" ",7) . $end;
      }
      $options='';
      if ( $parameters ) {
        $got = array('"true"','"TRUE"','"True"','"false"','"FALSE"', '"False"' );
        $want = array('true','true','true','false','false','false');
        $options = str_replace($got,$want,json_encode($parameters));
      }
      $output = $this->twig->processTemplate('partials/datatables.html.twig',
          [
            'id' => $id,
            'content' => $content,
            'options' => $options,
            'snippet' => $this->grav['datatables']
          ]);
        $this->grav['datatables'] = ''; // clear snippet for next table invocation
        return $output;
    });
  }
}
