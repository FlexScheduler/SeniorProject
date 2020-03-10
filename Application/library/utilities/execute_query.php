<?php
function execute_query($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        $exceptionMessage = "MySQL error: " . $conn->error;
        $conn->rollback();
        $conn->autocommit(TRUE);
        throw new Exception($exceptionMessage);
    }
    while ($conn->more_results()) {
      $conn->next_result();
    }

    return ($result);
}
?>
