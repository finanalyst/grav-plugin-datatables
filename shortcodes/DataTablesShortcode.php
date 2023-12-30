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

      // Get id from html and remove the id attribute
      $hasIdAttribute = preg_match('/(\<table\s[^>]*)id="(.*?)"(.*\>)/ims', $content, $matches);
      if ($hasIdAttribute == 1) {
        $content = $matches[1] . $matches[3];
        $id = $matches[2];
      }

      // Get id from parameters, overriding any existing id
      $id = trim($parameters['id'] ?? $parameters['grav-id'] ?? '');
      unset($parameters['id']);
      unset($parameters['grav-id']);

      // If there's no id or the id is invalid, generate a new id
      $invalidId = (preg_match('/\s/', $id) == 1);
      if ($invalidId || !$id) {
        $id = Utils::generateRandomString(10);
      }

      // Insert the id attribute
      $content = preg_replace('/\<table/i', "\\0 id=\"$id\"", $content);

      // JSON encode the shortcode parameters
      // Stripping quotes from "true" and "false", effectively casting to boolean
      $options = preg_replace(['/"true"/i', '/"false"/i'], ['true', 'false'], json_encode($parameters));

      // Process the template and decode the output
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
