<?php

namespace Core;

use Exception;
use ReflectionClass;

abstract class View
{

    protected $template;

    public function __construct()
    {
        $reflection = new ReflectionClass($this);
        $childFilename = $reflection->getFilename();
        $absolutePath = realpath($childFilename);

        // Clean path if on webserver
        $docroot = realpath($_SERVER['DOCUMENT_ROOT']) . '/';
        $relativePath = str_replace($docroot, '', $absolutePath);

        $this->template = preg_replace('/\.php$/', '.view.php', $relativePath);
    }

    public function Render($data = array())
    {
        extract($data);
        $props = get_object_vars($this);

        try {

            // Run template in output buffer
            ob_start();
            include($this->template);
            return ob_get_clean();

        } catch (\Throwable $e) {

            // Safely clean and close the buffer so it doesn't leak blank/broken HTML
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            // Log the full error context
            __log("Render failed in {$this->template}: {$e->getMessage()} on line {$e->getLine()}");

            return null;
        }

    }
}

?>