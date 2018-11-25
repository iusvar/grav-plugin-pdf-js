<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Grav\Common\Utils;


class PDFJsShortcode extends Shortcode
{
    protected $outerEscape = null;

    public function init()
    {
        $this->shortcode->getHandlers()->add('pdfjs', array($this, 'process'));
    }

    public function process(ShortcodeInterface $sc) {
        $fn = $sc->getParameter('file', null);
        if ($fn === null) {
            $fn = $sc->getShortcodeText();
            $fn = str_replace('[pdfjs=', '', $fn);
            $fn = str_replace('/]', '', $fn);
            $fn = str_replace(']', '', $fn);
            $fn = trim($fn);
        }
        if( strpos($fn, ' ') || strpos($fn, '"') ) {
          $fn = str_replace('"','',$fn);
        }

        if ( ($fn === null) && ($fn === '') ) {
            return "<p>PDFJs: Malformed shortcode (<tt>".htmlspecialchars($sc->getShortcodeText())."</tt>).</p>";
        }

        $width      = $sc->getParameter('width', null);
        $height     = $sc->getParameter('height', null);
        
        // Get absolute file name
        $abspath = null;
        if ($fn!== null) {
            $abspath = $this->getPath(static::sanitize($fn));
        }
        if ($abspath === null) {
            return "<p>PDFJs: Could not resolve file name '$fn'.</p>";
        }
        if (!file_exists($abspath)) {
            return "<p>PDFJs: Could not find the requested data file '$fn'.</p>";
        }

        $base_url = $this->grav['base_url_relative'];
        $dir = __DIR__;

        $parameters = array();
        
        $pos = strpos($dir, $base_url);
        $parameters['base_plugin'] = substr($dir,$pos);
        $parameters['technique'] = $this->config->get('plugins.pdf-js.technique');
        $parameters['show_used_technique'] = $this->config->get('plugins.pdf-js.show_used_technique');
        
        $pos = strpos($abspath, $base_url);
        $parameters['pdf_file'] = substr($abspath, $pos);

        $config_width = $this->config->get('plugins.pdf-js.width');
        $config_height = $this->config->get('plugins.pdf-js.height');
        $parameters['width'] = ( $width ? $width : $config_width );
        $parameters['height'] = ( $height ? $height : $config_height );

        $twig = $this->grav['twig'];
        $output = $twig->processTemplate('pdfjs.html.twig', ['parameters' => $parameters] );

        return $output;
    }

    private function getPath($fn) {
		$depo = null;
		$path = null;

		$colon = strpos($fn, ':');
		if ( $colon !== false ) {
			$depo = strstr($fn, ':', true);
			$path = $this->grav['locator']->findResource('user://'.$depo, true);
			$fn = substr(strrchr($fn, ':'), 1);
		}

		$slash = strrpos($fn, '/');
		if ( $slash !== false ) {
			$subpath = substr($fn,0,$slash);
			$fn = substr(strrchr($fn, "/"), 1);
			$path = $this->grav['locator']->findResource('user://'.$depo.DS.$subpath, true);
		}
		if ( $colon === false && $slash === false ) {
            $path = $this->grav['page']->path();
        }
        if ( (Utils::endswith($path, DS)) || (Utils::startswith($fn, DS)) ) {
            $path = $path . $fn;
        } else {
            $path = $path . DS . $fn;
        }
        if (file_exists($path)) {
            return $path;
        }
        return null;
    }

    private static function sanitize($fn) {
        $fn = trim($fn);
        $fn = str_replace('..', '', $fn);
        $fn = ltrim($fn, DS);
        $fn = str_replace(DS.DS, DS, $fn);
        return $fn;
    }
}
