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
    protected $script;

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
        $this->grav['datatables'] = $this->script = '';
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
