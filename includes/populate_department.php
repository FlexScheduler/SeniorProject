<?php
function populate_dropdown($dropdownID, $dept, $course, $instr)
{
    try {
        //TODO DON'T HARDCODE establish connection
        $conn = new mysqli(
            "localhost",
            "root",
            "",
            "successscheduler"
        );

        // filters
        $filter = "";
        if ($dept != "none" && $dropdownID != "deptID") {
            $filter .= "deptID = " . $dept;
        }
        if ($course != "none" && $dropdownID != "crseID") {
            if ($filter != "") {
                $filter .= " AND ";
            }
            $filter .= "crseID  = " . $course;
        }
        if ($instr != "none" && $dropdownID != "instr") {
            if ($filter != "") {
                $filter .= " AND ";
            }
            $filter .= "crseID  = " . $course;
        }

        // query departments
        $sql = "
            SELECT DISTINCT "
                . $dropdownID . "
            FROM
                Sections
            WHERE " .
                $filter  . ";";
        $result = execute_query($conn, $sql);

        // populate dropdown
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" .
                row[$dropdownID] .
                "'>" .
                row[$dropdownID] .
                "</option>";
        }
    }
    // error alert
    catch (Exception $e) {
        $errorAlert = $e->getMessage();
    }
}
