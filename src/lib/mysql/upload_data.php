<<<<<<< Updated upstream
<?php
function upload_data($conn) {
    // insert section data into Sections table
    $sql = "
        INSERT INTO Sections(
            deptID,
            crseID,
            secID,
            crseName,
            numCredit,
            strtDate,
            endDate,
            campus
        )
        SELECT
            deptID,
            crseID,
            secID,
            crseName,
            numCredit,
            strtDate,
            endDate,
            campus1
        FROM
            Temp;";
    $result = execute_query($conn, $sql);


    // sort Sections table
    $sql = "
        ALTER TABLE
            Sections
        ORDER BY
            deptID,
            crseID,
            secID ASC;";
    $result = execute_query($conn, $sql);


    // insert first arranged instructor data into ArrangedInstructors table
    $sql = "
        INSERT INTO ArrangedInstructors(
            deptID,
            crseID,
            secID,
            instrLName,
            instrFName
        )
        SELECT
            deptID,
            crseID,
            secID,
            SUBSTRING_INDEX(instrName1, ' ', 1),
            SUBSTRING_INDEX(instrName1, ' ', -1)
        FROM
            Temp
        WHERE
            offrDays1 = 'ARR';";
    $result = execute_query($conn, $sql);


    // insert second arranged instructor data into ArrangedInstructors table
    $sql = "
        INSERT INTO ArrangedInstructors(
            deptID,
            crseID,
            secID,
            instrLName,
            instrFName
        )
        SELECT
            deptID,
            crseID,
            secID,
            SUBSTRING_INDEX(instrName2, ' ', 1),
            SUBSTRING_INDEX(instrName2, ' ', -1)
        FROM
            Temp
        WHERE
            offrDays1 = 'ARR' AND instrName2 != '';";
    $result = execute_query($conn, $sql);


    // sort ArrangedInstructors table
    $sql = "
        ALTER TABLE
            ArrangedInstructors
        ORDER BY
            deptID,
            crseID,
            secID,
            instrLName,
            instrFName ASC;";
    $result = execute_query($conn, $sql);


    // split first session days into atmoic values
    $sql = "
        SELECT
            deptID,
            crseID,
            secID,
            offrDays1
        FROM
            Temp
        WHERE
            offrDays1 != 'ARR';";
    $resultDays = execute_query($conn, $sql);


    // insert first session data into Sessions table
    while($row = $resultDays->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays1']); ++$i) {
            $sql = "
                INSERT INTO Sessions(
                    deptID,
                    crseID,
                    secID,
                    dayID,
                    strtTime,
                    endTime,
                    bldgID,
                    rmNum
                )
                SELECT
                    deptID,
                    crseID,
                    secID,
                    '" . substr($row['offrDays1'], $i, 1) . "',
                    strtTime1,
                    endTime1,
                    bldgID1,
                    rmNum1
                FROM
                    Temp
                WHERE
                    deptID = '" . $row['deptID'] . "' AND
                    crseID = '" . $row['crseID'] . "' AND
                    secID = '" . $row['secID'] . "';";
            $result = execute_query($conn, $sql);

        }
    }

    // free days result
    $resultDays->free_result();

    // split second session days into atmoic values
    $sql = "
        SELECT
            deptID,
            crseID,
            secID,
            offrDays2
        FROM
            Temp
        WHERE
            offrDays1 != 'ARR' AND
            offrDays2 != '' AND(
                offrDays1 != offrDays2 OR
                strtTime1 != strtTime2 OR
                endTime1 != endTime2
            );";
    $resultDays = execute_query($conn, $sql);


    // insert second session data into Sessions table
    while($row = $resultDays->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays2']); ++$i) {
            $sql = "
                INSERT INTO Sessions(
                    deptID,
                    crseID,
                    secID,
                    dayID,
                    strtTime,
                    endTime,
                    bldgID,
                    rmNum
                )
                SELECT
                    deptID,
                    crseID,
                    secID,
                    '" . substr($row['offrDays2'], $i, 1) . "',
                    strtTime2,
                    endTime2,
                    bldgID2,
                    rmNum2
                FROM
                    Temp
                WHERE
                    deptID = '" . $row['deptID'] . "' AND
                    crseID = '" . $row['crseID'] . "' AND
                    secID = '" . $row['secID'] . "';";
            $result = execute_query($conn, $sql);

        }
    }

    // free days result
    $resultDays->free_result();

    // sort Sessions table
    $sql = "
        ALTER TABLE
            Sessions
        ORDER BY
            deptID,
            crseID,
            secID,
            dayID,
            strtTime,
            endTime ASC;";
    $result = execute_query($conn, $sql);


    // split first session days into atmoic values
    $sql = "
        SELECT
            deptID,
            crseID,
            secID,
            offrDays1,
            instrName1
        FROM
            Temp
        WHERE
            offrDays1 != 'ARR';";
    $resultDays = execute_query($conn, $sql);


    // insert first session data into SessionInstructors table
    while($row = $resultDays->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays1']); ++$i) {
            $sql = "
                INSERT INTO SessionInstructors(
                    deptID,
                    crseID,
                    secID,
                    dayID,
                    strtTime,
                    endTime,
                    instrLName,
                    instrFName
                )
                SELECT
                    deptID,
                    crseID,
                    secID,
                    '" . substr($row['offrDays1'], $i, 1) . "',
                    strtTime1,
                    endTime1,
                    SUBSTRING_INDEX(instrName1, ' ', 1),
                    SUBSTRING_INDEX(instrName1, ' ', -1)
                FROM
                    Temp
                WHERE
                    deptID = '" . $row['deptID'] . "' AND
                    crseID = '" . $row['crseID'] . "' AND
                    secID = '" . $row['secID'] . "';";
            $result = execute_query($conn, $sql);

        }
    }

    // free days result
    $resultDays->free_result();

    // split second session days into atmoic values
    $sql = "
        SELECT
            deptID,
            crseID,
            secID,
            offrDays2,
            instrName2
        FROM
            Temp
        WHERE
            offrDays1 != 'ARR' AND
            offrDays2 != '' AND
            instrName2 != '' AND(
                offrDays1 != offrDays2 OR
                strtTime1 != strtTime2 OR
                endTime1 != endTime2 OR
                instrName1 != instrName2
            );";
    $resultDays = execute_query($conn, $sql);


    // insert second session data into SessionsInstructors table
    while($row = $resultDays->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays2']); ++$i) {
            $sql = "
                INSERT INTO SessionInstructors(
                    deptID,
                    crseID,
                    secID,
                    dayID,
                    strtTime,
                    endTime,
                    instrLName,
                    instrFName
                )
                SELECT
                    deptID,
                    crseID,
                    secID,
                    '" . substr($row['offrDays2'], $i, 1) . "',
                    strtTime2,
                    endTime2,
                    SUBSTRING_INDEX(instrName2, ' ', 1),
                    SUBSTRING_INDEX(instrName2, ' ', -1)
                FROM
                    Temp
                WHERE
                    deptID = '" . $row['deptID'] . "' AND
                    crseID = '" . $row['crseID'] . "' AND
                    secID = '" . $row['secID'] . "';";
            $result = execute_query($conn, $sql);

        }
    }

    // free days result
    $resultDays->free_result();

    // sort SessionInstructors table
    $sql = "
        ALTER TABLE
            SessionInstructors
        ORDER BY
            deptID,
            crseID,
            secID,
            dayID,
            strtTime,
            endTime,
            instrLName,
            instrFName ASC";
    $result = execute_query($conn, $sql);


    return;
}
?>
=======
<?php
function upload_data($conn) {
    // insert section data into Sections table
    $sql = "
        INSERT INTO Sections(
            deptID,
            crseID,
            secID,
            crseName,
            numCredit,
            strtDate,
            endDate,
            campus
        )
        SELECT
            deptID,
            crseID,
            secID,
            crseName,
            numCredit,
            strtDate,
            endDate,
            campus1
        FROM
            Temp;";
    $result = execute_query($conn, $sql);


    // sort Sections table
    $sql = "
        ALTER TABLE
            Sections
        ORDER BY
            deptID,
            crseID,
            secID ASC;";
    $result = execute_query($conn, $sql);


    // insert first arranged instructor data into ArrangedInstructors table
    $sql = "
        INSERT INTO ArrangedInstructors(
            deptID,
            crseID,
            secID,
            instrLName,
            instrFName
        )
        SELECT
            deptID,
            crseID,
            secID,
            SUBSTRING_INDEX(instrName1, ' ', 1),
            SUBSTRING_INDEX(instrName1, ' ', -1)
        FROM
            Temp
        WHERE
            offrDays1 = 'ARR';";
    $result = execute_query($conn, $sql);


    // insert second arranged instructor data into ArrangedInstructors table
    $sql = "
        INSERT INTO ArrangedInstructors(
            deptID,
            crseID,
            secID,
            instrLName,
            instrFName
        )
        SELECT
            deptID,
            crseID,
            secID,
            SUBSTRING_INDEX(instrName2, ' ', 1),
            SUBSTRING_INDEX(instrName2, ' ', -1)
        FROM
            Temp
        WHERE
            offrDays1 = 'ARR' AND instrName2 != '';";
    $result = execute_query($conn, $sql);


    // sort ArrangedInstructors table
    $sql = "
        ALTER TABLE
            ArrangedInstructors
        ORDER BY
            deptID,
            crseID,
            secID,
            instrLName,
            instrFName ASC;";
    $result = execute_query($conn, $sql);


    // split first session days into atmoic values
    $sql = "
        SELECT
            deptID,
            crseID,
            secID,
            offrDays1
        FROM
            Temp
        WHERE
            offrDays1 != 'ARR';";
    $resultDays = execute_query($conn, $sql);


    // insert first session data into Sessions table
    while($row = $resultDays->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays1']); ++$i) {
            $sql = "
                INSERT INTO Sessions(
                    deptID,
                    crseID,
                    secID,
                    dayID,
                    strtTime,
                    endTime,
                    bldgID,
                    rmNum
                )
                SELECT
                    deptID,
                    crseID,
                    secID,
                    '" . substr($row['offrDays1'], $i, 1) . "',
                    strtTime1,
                    endTime1,
                    bldgID1,
                    rmNum1
                FROM
                    Temp
                WHERE
                    deptID = '" . $row['deptID'] . "' AND
                    crseID = '" . $row['crseID'] . "' AND
                    secID = '" . $row['secID'] . "';";
            $result = execute_query($conn, $sql);

        }
    }

    // free days result
    $resultDays->free_result();

    // split second session days into atmoic values
    $sql = "
        SELECT
            deptID,
            crseID,
            secID,
            offrDays2
        FROM
            Temp
        WHERE
            offrDays1 != 'ARR' AND
            offrDays2 != '' AND(
                offrDays1 != offrDays2 OR
                strtTime1 != strtTime2 OR
                endTime1 != endTime2
            );";
    $resultDays = execute_query($conn, $sql);


    // insert second session data into Sessions table
    while($row = $resultDays->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays2']); ++$i) {
            $sql = "
                INSERT INTO Sessions(
                    deptID,
                    crseID,
                    secID,
                    dayID,
                    strtTime,
                    endTime,
                    bldgID,
                    rmNum
                )
                SELECT
                    deptID,
                    crseID,
                    secID,
                    '" . substr($row['offrDays2'], $i, 1) . "',
                    strtTime2,
                    endTime2,
                    bldgID2,
                    rmNum2
                FROM
                    Temp
                WHERE
                    deptID = '" . $row['deptID'] . "' AND
                    crseID = '" . $row['crseID'] . "' AND
                    secID = '" . $row['secID'] . "';";
            $result = execute_query($conn, $sql);

        }
    }

    // free days result
    $resultDays->free_result();

    // sort Sessions table
    $sql = "
        ALTER TABLE
            Sessions
        ORDER BY
            deptID,
            crseID,
            secID,
            dayID,
            strtTime,
            endTime ASC;";
    $result = execute_query($conn, $sql);


    // split first session days into atmoic values
    $sql = "
        SELECT
            deptID,
            crseID,
            secID,
            offrDays1,
            instrName1
        FROM
            Temp
        WHERE
            offrDays1 != 'ARR';";
    $resultDays = execute_query($conn, $sql);


    // insert first session data into SessionInstructors table
    while($row = $resultDays->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays1']); ++$i) {
            $sql = "
                INSERT INTO SessionInstructors(
                    deptID,
                    crseID,
                    secID,
                    dayID,
                    strtTime,
                    endTime,
                    instrLName,
                    instrFName
                )
                SELECT
                    deptID,
                    crseID,
                    secID,
                    '" . substr($row['offrDays1'], $i, 1) . "',
                    strtTime1,
                    endTime1,
                    SUBSTRING_INDEX(instrName1, ' ', 1),
                    SUBSTRING_INDEX(instrName1, ' ', -1)
                FROM
                    Temp
                WHERE
                    deptID = '" . $row['deptID'] . "' AND
                    crseID = '" . $row['crseID'] . "' AND
                    secID = '" . $row['secID'] . "';";
            $result = execute_query($conn, $sql);

        }
    }

    // free days result
    $resultDays->free_result();

    // split second session days into atmoic values
    $sql = "
        SELECT
            deptID,
            crseID,
            secID,
            offrDays2,
            instrName2
        FROM
            Temp
        WHERE
            offrDays1 != 'ARR' AND
            offrDays2 != '' AND
            instrName2 != '' AND(
                offrDays1 != offrDays2 OR
                strtTime1 != strtTime2 OR
                endTime1 != endTime2 OR
                instrName1 != instrName2
            );";
    $resultDays = execute_query($conn, $sql);


    // insert second session data into SessionsInstructors table
    while($row = $resultDays->fetch_assoc()) {
        for ($i = 0; $i < strlen($row['offrDays2']); ++$i) {
            $sql = "
                INSERT INTO SessionInstructors(
                    deptID,
                    crseID,
                    secID,
                    dayID,
                    strtTime,
                    endTime,
                    instrLName,
                    instrFName
                )
                SELECT
                    deptID,
                    crseID,
                    secID,
                    '" . substr($row['offrDays2'], $i, 1) . "',
                    strtTime2,
                    endTime2,
                    SUBSTRING_INDEX(instrName2, ' ', 1),
                    SUBSTRING_INDEX(instrName2, ' ', -1)
                FROM
                    Temp
                WHERE
                    deptID = '" . $row['deptID'] . "' AND
                    crseID = '" . $row['crseID'] . "' AND
                    secID = '" . $row['secID'] . "';";
            $result = execute_query($conn, $sql);

        }
    }

    // free days result
    $resultDays->free_result();

    // sort SessionInstructors table
    $sql = "
        ALTER TABLE
            SessionInstructors
        ORDER BY
            deptID,
            crseID,
            secID,
            dayID,
            strtTime,
            endTime,
            instrLName,
            instrFName ASC";
    $result = execute_query($conn, $sql);


    return;
}
?>
>>>>>>> Stashed changes
