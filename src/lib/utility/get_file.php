<<<<<<< Updated upstream
<?php
function get_file($fileName) {
    // get file information
    $targetDir = "../../csv/";
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // check file is good
    if ($fileType != "csv") {
        $exceptionMessage = "File type error: chosen file must be in " .
            "CSV format.";
        throw new Exception($exceptionMessage);
    }
    if (!move_uploaded_file(
            $_FILES["uploadFile"]["tmp_name"],
            $targetFilePath)
        ) {
        $exceptionMessage = "File upload error: file could not be uploaded " .
            "to the server.";
        throw new Exception($exceptionMessage);
    }

    return ($targetFilePath);
}
?>
=======
<?php
function get_file($fileName) {
    // get file information
    $targetDir = "../../csv/";
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // check file is good
    if ($fileType != "csv") {
        $exceptionMessage = "File type error: chosen file must be in " .
            "CSV format.";
        throw new Exception($exceptionMessage);
    }
    if (!move_uploaded_file(
            $_FILES["uploadFile"]["tmp_name"],
            $targetFilePath)
        ) {
        $exceptionMessage = "File upload error: file could not be uploaded " .
            "to the server.";
        throw new Exception($exceptionMessage);
    }

    return ($targetFilePath);
}
?>
>>>>>>> Stashed changes
