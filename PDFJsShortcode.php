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
        if ( ($fn === null) && ($fn === '') ) {
            return "<p>PDFJs: Malformed shortcode (<tt>".htmlspecialchars($sc->getShortcodeText())."</tt>).</p>";
        }

        // Get absolute file name
        $abspath = null;
        if ($fn!== null) {
            $abspath = $this->getPath(static::sanitize($fn));
        }
        if ($abspath === null) {
            return "<p>PDFJs: '$abspath' Could not resolve file name '$fn'.</p>";
        }
        if (!file_exists($abspath)) {
            return "<p>PDFJs: Could not find the requested data file '$fn'.</p>";
        }

		$base_url = $this->grav['base_url_relative'];
		$dir = __DIR__;
		
		$pos = strpos($dir, $base_url);
		$base_plugin = substr($dir,$pos);
		
		$pos = strpos($abspath, $base_url);
		$pdf_file = substr($abspath, $pos);

		$height = $this->config->get('plugins.pdf-js.height');
		$height = ( $height ? $height : 300 );
		
		/*$page = $this->grav['page'];
		$this->grav['debugger']->addMessage( $page );
		$this->grav['debugger']->addMessage( __DIR__ );
		$this->grav['debugger']->addMessage( 'abspath: '.$abspath );
		$this->grav['debugger']->addMessage( 'base_url: '.$base_url );
		$this->grav['debugger']->addMessage( 'pos: '.$pos );
		$this->grav['debugger']->addMessage( 'file_pdf: '.$pdf_file );
		$this->grav['debugger']->addMessage( 'base_plugin: '.$base_plugin );*/

        $twig = $this->grav['twig'];
        $output = $twig->processTemplate('pdfjs.html.twig', [ 'base_plugin' => $base_plugin, 'pdf_file' => $pdf_file, 'height' => $height ] );

        return $output;
    }

    private function getPath($fn) {
        if (Utils::startswith($fn, 'data:')) {
            $path = $this->grav['locator']->findResource('user://data', true);
            $fn = str_replace('data:', '', $fn);
        } elseif (Utils::startswith($fn, 'pdfs:')) {
			$path = $this->grav['locator']->findResource('user://pdfs', true);
			$fn = str_replace('pdfs:', '', $fn);
		} else {
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
