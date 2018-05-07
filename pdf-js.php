<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class TableImporterPlugin
 * @package Grav\Plugin
 */
class PDFJsPlugin extends Plugin
{

    protected $outerEscape = null;

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            return;
        }

        $this->enable([
			'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
			'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
			'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
        ]);
    }

    public function onShortcodeHandlers(Event $e)
    {
        $this->grav['shortcode']->registerShortcode('PDFJsShortcode.php', __DIR__);
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onTwigSiteVariables()
    {
        $this->grav['assets']->add('plugin://pdf-js/web/viewer.min.css');
        
		//$this->grav['assets']->add('plugin://pdf-js/build/pdf.js');
		$this->grav['assets']->add('https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.489/pdf.min.js');

		//$this->grav['assets']->add('plugin://pdf-js/build/pdf.worker.js');
		$this->grav['assets']->add('https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.489/pdf.worker.js');
    }
}
