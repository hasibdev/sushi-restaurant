<?php

$file = file_get_contents(__DIR__ . '/App/Backups/DB/jara_db_backup_23_02_22_06_02_54.sql');

preg_match_all("/[a-f0-9]{32}(.webp|.jpg|.png|.jpeg)/i", $file, $matches);
$indir = scandir(__DIR__ . '/public/uploads/images/');

$indb = $matches[0];

foreach ($indir as $file) {
    if (in_array($file, $indb)) {
        echo "Available: $file\n";
    } else {
        echo "Unavailable: $file\n";
    }
}
