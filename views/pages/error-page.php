<?php

namespace Views;
use Core\DocumentView;

class ErrorPage extends DocumentView
{
    public ?PageHead $head;
    protected int $response_code = 404;
    public string $title = '';
    public string $description = '';
        
    public function __construct($response_name = '404', $response_code = 404)
    {
        parent::__construct($response_code);

        $this->head = new PageHead();
        $this->title = __("errors.{$response_name}.title");
        $this->description = __("errors.{$response_name}.description");
        $this->response_code = $response_code;
    }
}

?>
