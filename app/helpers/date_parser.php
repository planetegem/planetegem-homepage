<?php

// Parse timestamp into readable date
function date_parser($timestamp)
{
    $parsed_timestamp = date_parse($timestamp);

    return
        sprintf('%02d', $parsed_timestamp['day']) .
        '-' . sprintf('%02d', $parsed_timestamp['month']) .
        '-' . $parsed_timestamp['year'];
}
?>