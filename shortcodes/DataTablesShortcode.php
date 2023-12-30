<?php

namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Grav\Common\Utils;

class DataTablesShortcode extends Shortcode
{
  public function init()
  {
    $this->shortcode->getHandlers()->add('datatables', function (ShortcodeInterface $sc) {
      $content = $sc->getContent();
      $parameters = $sc->getParameters();
      $id = '';

      $hasTable = preg_match('/\<table[^>]*?\>(.*)\<\/table[^>]*\>/ims', $content);
      if ($hasTable === FALSE or $hasTable == 0) {
        return htmlspecialchars_decode($this->twig->processTemplate(
          'partials/datatables-error.html.twig',
          [
            'message' => 'Shortcode content does not appear to have a valid &lt;table&gt;...&lt;/table&gt; element. Got instead:',
            'content' => $content
          ]
        ));
      }

      $hasIdAttribute = preg_match('/(\<table\s[^>]*)id="(.*?)"(.*\>)/ims', $content, $matches);
      if ($hasIdAttribute == 1) {
        $invalidId = (!$matches[2] || preg_match('/\s/', $matches[2]) == 1);
        if ($invalidId) {
          $content = $matches[1] . $matches[3];
        } else {
          $id = $matches[2];
        }
      }

      if (!$id) {
        $id = trim($parameters['id'] ?? $parameters['grav-id'] ?? '');
        unset($parameters['id']);
        unset($parameters['grav-id']);
        if (!$id) $id = Utils::generateRandomString(10);
        $pos = stripos('<table', $content);
        $end = substr($content, $pos);
        $content = substr_replace($content, " id=\"$id\" ", 7) . $end;
      }

      $options = preg_replace(['/"true"/i', '/"false"/i'], ['true', 'false'], json_encode($parameters));
      $output = $this->twig->processTemplate(
        'partials/datatables.html.twig',
        [
          'id' => $id,
          'content' => $content,
          'options' => $options,
          'snippet' => $this->grav['datatables']
        ]
      );
      $output = htmlspecialchars_decode($output);
      $this->grav['datatables'] = '';
      return $output;
    });
  }
}
