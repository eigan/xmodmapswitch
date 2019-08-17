<?php

/**
 * Path where you keep your .Xmodmap files
 */
$maps = $_SERVER['HOME'] . '/.Xmodmaps/';

/**
 * Path to .Xmodmap (read by .Xmodmap on x init etc)
 */
$masterPath = $_SERVER['HOME'] . '/.Xmodmap';

/**
 * Start of script
 */
$mapFiles = [];
foreach(scandir($maps) as $maFile) {
    if(strlen($maFile) > 2) {
        $mapFiles[] = $maps . $maFile;
    }
}

$isNext = false;
$nextModmap = null;
foreach ($mapFiles as $xmodmap) {
    // Find the current in use
    if ($isNext) {
        $nextModmap = $xmodmap;
       break;
    }

    if (filesize($xmodmap) === filesize($masterPath)) {
        if (md5_file($xmodmap) === md5_file($masterPath)) {
            $isNext = true;
        }
    }
}

if ($nextModmap === null) {
    $nextModmap = reset($mapFiles);
}

$nextMapped = mapFile($nextModmap);
$previousMapped = mapFile($masterPath);

function mapFile($file) {
    $mappedFile = [];

    foreach (file($file) as $line) {
        $equalPos = strpos($line, "=");

        $key = substr($line, 0, $equalPos);
        $value = substr($line, $equalPos);

        $mappedFile[$key] = trim($value);
    }

    return $mappedFile;
}

$newMap = "";

foreach($nextMapped as $code => $value) {
    if (isset($previousMapped[$code]) && $previousMapped[$code] === $value) {
        // Ignore similar
        continue;
    }

    $newMap .= $code . $value . "\n";
}

copy($nextModmap, "$masterPath");
file_put_contents("$masterPath.tmp", $newMap);
shell_exec("xmodmap $masterPath.tmp");
unlink("$masterPath.tmp");
