<<<<<<< Updated upstream
<?php
function drop_temp($conn) {
    $sql = "
        DROP TABLE
            Temp;";
    $result = execute_query($conn, $sql);


    return;
}
?>
=======
<?php
function drop_temp($conn) {
    $sql = "
        DROP TABLE
            Temp;";
    $result = execute_query($conn, $sql);


    return;
}
?>
>>>>>>> Stashed changes
