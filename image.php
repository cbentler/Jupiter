<?php

    $file = basename(urldecode($_GET['file']));
    $fileDir = '/Users/cbentle/Documents/Programming/File_Server/';

    if (file_exists($fileDir . $file))
    {
        // Note: You should probably do some more checks
        // on the filetype, size, etc.
        $contents = file_get_contents($fileDir . $file);

        // Note: You should probably implement some kind
        // of check on filetype
        header('Content-type: image/png');

        echo $file;
        echo $fileDir;

        echo $contents;
    }
    echo $fileDir;
    echo $file;


    echo $contents;

?>
