<<<<<<< Updated upstream
<?php
function delete_term($conn, $strtMonth, $endMonth) {
    // delete existing data for selected term
    $sql = "
        DELETE
        FROM
            Sections
        WHERE
            MONTH(strtDate) >= " . $strtMonth . " AND
            MONTH(endDate) <= " . $endMonth . ";";
    $result = execute_query($conn, $sql);

    return;
}
?>
=======
<?php
function delete_term($conn, $strtMonth, $endMonth) {
    // delete existing data for selected term
    $sql = "
        DELETE
        FROM
            Sections
        WHERE
            MONTH(strtDate) >= " . $strtMonth . " AND
            MONTH(endDate) <= " . $endMonth . ";";
    $result = execute_query($conn, $sql);

    return;
}
?>
>>>>>>> Stashed changes
