<?php

abstract class View {

    protected $template;

    public function __construct(){
        $reflection = new ReflectionClass($this);
        $childFilename = $reflection->getFilename();
		$absolutePath = realpath($childFilename);
		
		// Clean path if on webserver
		$docroot = realpath($_SERVER['DOCUMENT_ROOT']) . '/';
		$relativePath = str_replace($docroot, '', $absolutePath);
		
        $this->template = preg_replace('/\.php$/', '.view.php', $relativePath);
    }

    public function Render($data = array()){
        extract($data);
        $props = get_object_vars($this);

        ob_start();
            include($this->template);
        return ob_get_clean();
    }
}

?>