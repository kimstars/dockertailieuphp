<?php 

/**
 * Navigate to public path to get files inside
 *
 * @param [type] $path
 * @return void
 */
function dateFromStr($str, $format)
{
    $date=date_create($str);
    return date_format($date, $format);
}

function now()
{
    return date("Y-m-d H:i:s");
}
