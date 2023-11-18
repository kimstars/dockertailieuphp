<?php

$target_temp = __DIR__ . "/public/storage/temp";
if (file_exists($target_temp)) {
    $files = scandir($target_temp);
    $files = array_filter($files, function ($value) {
        return !in_array($value, ['.', '..']);
    });
    foreach ($files as $file) {
        $path = $target_temp."/".$file;
        $file_creation_date = filectime($path);
        $create_time = new DateTime(date('Y-m-d H:i:s', $file_creation_date));
        $diff = $create_time->diff(new DateTime()); 
        $total_minutes = ($diff->days * 24 * 60); 
        $total_minutes += ($diff->h * 60); 
        $total_minutes += $diff->i; 
        if ($total_minutes > 5) {
            unlink($path);
        }
    }
}
