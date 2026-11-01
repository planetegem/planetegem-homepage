<?php

namespace Core;
use ReflectionClass;

abstract class SingleFileView
{

    public function __tostring(): string
    {
        return $this->Render();
    }
    public function Render()
    {
        $reflection = new ReflectionClass($this);
        $filename = $reflection->getFilename();

        // Read the file contents as a string
        $fileContent = file_get_contents($filename);

        // Find __HALT_COMPILER(); and grab everything after the closing tag following it
        $htmlTemplate = '';
        $pos = stripos($fileContent, '__HALT_COMPILER();');
        if ($pos !== false) {
            // Slice everything after the halt command
            $afterHalt = substr($fileContent, $pos);
            $parts = explode('?>', $afterHalt, 2);
            if (isset($parts[1])) {
                $htmlTemplate = trim($parts[1]);
            }
        }

        try {
            ob_start();
            eval ('?>' . $htmlTemplate);
            return ob_get_clean() ?: 'empty';

        } catch (\Throwable $e) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            __log("Render failed in {$filename}: {$e->getMessage()} on line {$e->getLine()}");
            return null;
        }
    }
}