<?php
function get_file($fileName)
{
    // get file information
    $targetDir = "catalogs/";
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // check file is good
    if ($fileType != "csv") {
        $exceptionMessage = "File type error: chosen file must be in " .
            "CSV format.";
        throw new Exception($exceptionMessage);
    }

    // check if file already exists on server
    if (file_exists($fileName)) {
        chmod($fileName, 0755);
        unlink($fileName);
    }

    // check file uploaded to server
    if (!move_uploaded_file(
        $_FILES["fileSelect"]["tmp_name"],
        $targetFilePath
    )
        ) {
        $exceptionMessage = "File upload error: file could not be uploaded " .
            "to the server.";
        throw new Exception($exceptionMessage);
    }

    return ($targetFilePath);
}
