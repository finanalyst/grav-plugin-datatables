<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class DatatablesPlugin
 * @package Grav\Plugin
 */
class DatatablesPlugin extends Plugin
{
    protected $datatables;

    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths',0],
            'onAssetsInitialized' => ['onAssetsInitialized',0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }
        $this->datatables['script'] = '';
        $this->datatables['utils'] = new Datatables_utilities();
        $this->grav['datatables'] = $this->datatables;
    }

    public function onAssetsInitialized() {
        // Add JQuery plugin assets
        $this->grav['assets']->addCss( 'plugin://datatables/assets/datatables.min.css');
        $this->grav['assets']->addJs( 'plugin://datatables/assets/datatables.min.js');
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onShortcodeHandlers(Event $e)
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__.'/shortcodes');
    }

}

class Datatables_utilities {

    public function format($id, $table_options=[], $asset_options = ['group' => 'bottom']) {
        // suggested/example invocation: {% do grav.datatables.utils.format('mytable', {'pageLength': 25}) %}
        global $grav;

        $options = ( empty($table_options) ? '' : json_encode($table_options) );

        $js = <<<EOJ
        \$(document).ready( function () {
            \$('#{$id}').DataTable({$options});
        } );
EOJ;
        $grav['assets']->addInlineJs($js, $asset_options);
        
    }
}
