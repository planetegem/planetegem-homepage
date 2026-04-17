<?php

// QUERY CONSTRUCTOR: take a deconstructed query and rebuilds it into something usable for the SysInvAdmin API
function query_constructor($deconstructed_query)
{
    $query_string = '';

    // Check if filtering by category
    if (isset($deconstructed_query['category'])) {
        foreach ($deconstructed_query['category'] as $category) {
            $query_string .= "categories[]={$category}&";
        }
    }

    // Check if filtering by language
    if (isset($deconstructed_query['language'])) {
        foreach ($deconstructed_query['language'] as $language) {
            $query_string .= "languages[]={$language}&";
        }
    }

    // Check if specific order is required
    if (isset($deconstructed_query['order']))
        $query_string .= "order={$deconstructed_query['order'][0]}";

    // Clean query (add leading '?' and remove final '&' if necessary)
    if ($query_string != '') $query_string = '?' . $query_string;
    $query_string = preg_replace('/&$/', '', $query_string);

    return $query_string;
}

?>