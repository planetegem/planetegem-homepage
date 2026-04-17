<?php

// QUERY DECONSTRUCTOR: USED TO MANUALLY CONVERT QUERY STRING TO ARRAY(S)
// Does 2 Things:
// 1. If value is comma seperated list, explode into array
// 2. If query string contains identical keys, add their values together in an array (instead of the default behaviour, which would overwrite)

function query_deconstructor($query_string){

    if (!empty($query_string)){

        // Manually parse the query string for key/value pairs (and immediately explode comma deperated values)
        $value_pairs = array_map(function($query_pair){
                $value_pair = explode('=', $query_pair);
                if (count($value_pair) == 2){
                    return [$value_pair[0] => explode(',', $value_pair[1])];
                }
                return [];
            }, explode('&', $query_string));

        // Crteate an associative array from the key/value pairs
        $merged_query = [];
        foreach ($value_pairs as $value_pair){
            foreach ($value_pair as $key => $value){
                // Sanitize empty values
                $cleaned_value = array_filter($value);
                if (!$cleaned_value) continue;
                
                // Merge key/value pairs with identical keys
                if (isset($merged_query[$key])){
                    $merged_query[$key] = array_merge((array)$merged_query[$key], (array)$cleaned_value);
                } else {
                    $merged_query[$key] = $cleaned_value;
                }
            }
        }
        return $merged_query;
    }
    return [];
}

?>

