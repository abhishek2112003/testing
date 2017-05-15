
<?php

ob_start();

function create_zip($files = array(), $destination = '', $overwrite = false) {

    //if the zip file already exists and overwrite is false, return false
    if (file_exists($destination) && !$overwrite) {
        return false;
    }
    //vars
    $valid_files = array();
    //if files were passed in...
    if (is_array($files)) {
        //cycle through each file
        foreach ($files as $file) {
            //make sure the file exists
            if (file_exists($file)) {
                $valid_files[] = $file;
            }
        }
    }
    //if we have good files...
    if (count($valid_files)) {
        //create the archive
        $zip = new ZipArchive();
        if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            return false;
        }
        //add the files
        foreach ($valid_files as $file) {
            $zip->addFile($file, $file);
        }
        //debug
        //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
        //close the zip -- done!
        $zip->close();

        //then send the headers to foce download the zip file
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=my-archive.zip");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile("my-archive.zip");

        unlink('my-archive.zip');
        exit;

        //check to make sure the file exists
        return file_exists($destination);
    } else {
        return false;
    }
}

ob_end_flush();
?>
