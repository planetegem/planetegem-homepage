<?php

namespace Views;
use Core\View;

class Pager extends View
{
    public function __construct(
        public int $count,
        public int $current_page,
        public int $last_page,
        public int $per_page
    ) {
        parent::__construct();
    }
}

?>